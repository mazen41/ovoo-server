@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/lame.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function getRecorderContext() {
                return window.__inboxRecorder || {};
            }

            function formatRecordingTime(totalSeconds) {
                const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                const seconds = String(totalSeconds % 60).padStart(2, '0');
                return `${minutes}:${seconds}`;
            }

            function updateRecordingTimer() {
                const context = getRecorderContext();
                context.$voiceRecorderTimer?.text(formatRecordingTime(context.recordingSeconds ?? 0));
            }

            function updateRecorderUiState() {
                const context = getRecorderContext();
                const isRecording = context.mediaRecorder && context.mediaRecorder.state === 'recording';
                const hasPreview = context.hasRecordedPreview === true;

                context.$voiceRecorderStart?.prop('disabled', isRecording);
                context.$voiceRecorderStop?.prop('disabled', !isRecording);
                context.$voiceRecorderSend?.prop('disabled', !isRecording && !hasPreview);
                context.$voiceRecorderWave?.toggleClass('is-idle', !isRecording);
                context.$voiceRecorderPanel?.toggleClass('is-recording', isRecording);
                context.$voiceRecorderLabel?.text(isRecording ? 'Recording' : hasPreview ? 'Ready to send' : 'Ready');
            }

            function showVoiceRecorderPanel() {
                const context = getRecorderContext();
                context.$voiceRecorderPanel?.removeClass('d-none');
                $('body').addClass('voice-recorder-active');
                updateRecordingTimer();
                updateRecorderUiState();
            }

            function hideVoiceRecorderPanel() {
                const context = getRecorderContext();
                context.$voiceRecorderPanel?.addClass('d-none').removeClass('is-recording');
                $('body').removeClass('voice-recorder-active');
            }

            function stopRecordingStream() {
                const context = getRecorderContext();

                if (context.recordingStream) {
                    context.recordingStream.getTracks().forEach(track => track.stop());
                    context.recordingStream = null;
                }
            }

            function clearRecordingTimers() {
                const context = getRecorderContext();

                if (context.recordingTimerInterval) {
                    clearInterval(context.recordingTimerInterval);
                    context.recordingTimerInterval = null;
                }

                if (context.recordingStopTimeout) {
                    clearTimeout(context.recordingStopTimeout);
                    context.recordingStopTimeout = null;
                }
            }

            function resetVoiceRecorderState() {
                const context = getRecorderContext();

                clearRecordingTimers();
                context.recordingSeconds = 0;
                updateRecordingTimer();
                hideVoiceRecorderPanel();
                stopRecordingStream();
                context.recordingChunks = [];
                context.mediaRecorder = null;
                context.recordingShouldAutoSend = false;
                context.recordingWasCancelled = false;
                context.hasRecordedPreview = false;
                updateRecorderUiState();
            }

            function clearActiveRecordingState() {
                const context = getRecorderContext();

                clearRecordingTimers();
                stopRecordingStream();
                context.mediaRecorder = null;
                context.recordingChunks = [];
                context.recordingSeconds = 0;
                context.recordingShouldAutoSend = false;
                context.recordingWasCancelled = false;
                updateRecordingTimer();
                updateRecorderUiState();
            }

            function getSupportedAudioMimeType() {
                const mimeTypes = [
                    'audio/webm;codecs=opus',
                    'audio/webm',
                    'audio/mp4',
                    'audio/ogg;codecs=opus'
                ];

                return mimeTypes.find(type => window.MediaRecorder && MediaRecorder.isTypeSupported(type)) || '';
            }

            function assignRecordedAudioToInput(file) {
                const context = getRecorderContext();
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                context.$voiceRecorderInput[0].files = dataTransfer.files;
            }

            function convertFloat32ToInt16(floatArray) {
                const int16Array = new Int16Array(floatArray.length);

                for (let i = 0; i < floatArray.length; i++) {
                    const sample = Math.max(-1, Math.min(1, floatArray[i]));
                    int16Array[i] = sample < 0 ? sample * 0x8000 : sample * 0x7fff;
                }

                return int16Array;
            }

            async function convertRecordedBlobToMp3(blob) {
                if (!window.AudioContext || !window.lamejs) {
                    throw new Error('MP3 conversion is not supported in this browser.');
                }

                const audioContext = new AudioContext();

                try {
                    const arrayBuffer = await blob.arrayBuffer();
                    const audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
                    const channels = audioBuffer.numberOfChannels > 1 ? 2 : 1;
                    const sampleRate = audioBuffer.sampleRate;
                    const leftChannel = convertFloat32ToInt16(audioBuffer.getChannelData(0));
                    const rightChannel = channels === 2 ?
                        convertFloat32ToInt16(audioBuffer.getChannelData(1)) :
                        leftChannel;
                    const encoder = new lamejs.Mp3Encoder(channels, sampleRate, 128);
                    const mp3Chunks = [];
                    const chunkSize = 1152;

                    for (let i = 0; i < leftChannel.length; i += chunkSize) {
                        const leftChunk = leftChannel.subarray(i, i + chunkSize);
                        const encodedChunk = channels === 2 ?
                            encoder.encodeBuffer(leftChunk, rightChannel.subarray(i, i + chunkSize)) :
                            encoder.encodeBuffer(leftChunk);

                        if (encodedChunk.length > 0) {
                            mp3Chunks.push(encodedChunk);
                        }
                    }

                    const flushedChunk = encoder.flush();

                    if (flushedChunk.length > 0) {
                        mp3Chunks.push(flushedChunk);
                    }

                    return new Blob(mp3Chunks, {
                        type: 'audio/mpeg'
                    });
                } finally {
                    if (audioContext.state !== 'closed') {
                        await audioContext.close().catch(() => {});
                    }
                }
            }

            function openVoiceRecorderPanel() {
                const context = getRecorderContext();

                if (window.isInteractiveMessage?.() || context.getIsSubmitting?.()) {
                    return;
                }

                context.recordingSeconds = 0;
                context.recordingChunks = [];
                context.recordingShouldAutoSend = false;
                context.recordingWasCancelled = false;
                context.hasRecordedPreview = false;
                updateRecordingTimer();
                showVoiceRecorderPanel();
            }

            function stopVoiceRecording(autoSend = false) {
                const context = getRecorderContext();
                context.recordingShouldAutoSend = autoSend;

                if (context.mediaRecorder && context.mediaRecorder.state !== 'inactive') {
                    context.mediaRecorder.stop();
                }
            }

            function cancelVoiceRecording() {
                const context = getRecorderContext();
                context.recordingWasCancelled = true;
                window.clearImagePreview?.();

                if (context.mediaRecorder && context.mediaRecorder.state !== 'inactive') {
                    context.mediaRecorder.stop();
                    return;
                }

                resetVoiceRecorderState();
            }

            async function startVoiceRecording() {
                const context = getRecorderContext();

                if (window.isInteractiveMessage?.() || context.getIsSubmitting?.()) {
                    return;
                }

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia || !window.MediaRecorder) {
                    notify('error', 'Voice recording is not supported in this browser.');
                    return;
                }

                if (context.mediaRecorder && context.mediaRecorder.state === 'recording') {
                    return;
                }

                window.clearImagePreview?.();
                context.hasRecordedPreview = false;
                updateRecorderUiState();

                if (!navigator.permissions || !navigator.permissions.query) {
                    detectAndStartMicrophone();
                    return;
                }

                try {
                    const permission = await navigator.permissions.query({
                        name: 'microphone'
                    });

                    if (permission.state === 'denied') {
                        notify('error', 'Microphone access is blocked. Please allow microphone access in browser settings.');
                        return;
                    }

                    detectAndStartMicrophone();

                    permission.onchange = function() {
                        if (permission.state === 'denied') {
                            notify('error', 'Microphone access is blocked. Please allow microphone access in browser settings.');
                        }
                    };

                } catch (error) {
                    detectAndStartMicrophone();
                }
            }

            async function detectAndStartMicrophone() {
                const context = getRecorderContext();

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });

                    const mimeType = getSupportedAudioMimeType();

                    context.recordingStream = stream;
                    context.recordingChunks = [];
                    context.recordingSeconds = 0;
                    context.recordingShouldAutoSend = false;
                    context.recordingWasCancelled = false;
                    context.hasRecordedPreview = false;

                    updateRecordingTimer();
                    showVoiceRecorderPanel();

                    context.mediaRecorder = mimeType ?
                        new MediaRecorder(stream, {
                            mimeType
                        }) :
                        new MediaRecorder(stream);

                    context.mediaRecorder.addEventListener('dataavailable', function(event) {
                        if (event.data && event.data.size > 0) {
                            context.recordingChunks.push(event.data);
                        }
                    });

                    context.mediaRecorder.addEventListener('stop', function() {
                        if (context.recordingWasCancelled) {
                            resetVoiceRecorderState();
                            return;
                        }

                        if (!context.recordingChunks.length) {
                            clearActiveRecordingState();
                            notify('error', 'No audio was captured. Please try again.');
                            return;
                        }

                        const shouldAutoSend = context.recordingShouldAutoSend;
                        const recordedMimeType = context.mediaRecorder?.mimeType || mimeType || 'audio/webm';
                        const blob = new Blob(context.recordingChunks, {
                            type: recordedMimeType
                        });

                        convertRecordedBlobToMp3(blob)
                            .then(function(mp3Blob) {
                                const file = new File(
                                    [mp3Blob],
                                    `voice-message-${Date.now()}.mp3`, {
                                        type: 'audio/mpeg'
                                    }
                                );

                                assignRecordedAudioToInput(file);
                                context.$voiceRecorderInput.trigger('change');
                                context.hasRecordedPreview = true;
                            })
                            .catch(function() {
                                notify('error', 'The recording was captured, but MP3 conversion failed. Please try again.');
                            })
                            .finally(function() {
                                clearActiveRecordingState();

                                if (shouldAutoSend && context.$voiceRecorderInput[0].files.length) {
                                    context.hasRecordedPreview = false;
                                    hideVoiceRecorderPanel();
                                    updateRecorderUiState();
                                    setTimeout(() => {
                                        context.$messageForm.trigger('submit');
                                    }, 150);
                                    return;
                                }

                                hideVoiceRecorderPanel();
                                updateRecorderUiState();
                            });
                    });

                    context.mediaRecorder.start();
                    updateRecorderUiState();

                    context.recordingTimerInterval = setInterval(function() {
                        context.recordingSeconds++;
                        updateRecordingTimer();

                        if (context.recordingSeconds >= context.maxRecordingSeconds) {
                            stopVoiceRecording(false);
                        }
                    }, 1000);

                    context.recordingStopTimeout = setTimeout(function() {
                        stopVoiceRecording(false);
                    }, context.maxRecordingSeconds * 1000);

                } catch (error) {
                    clearActiveRecordingState();

                    if (error?.name === 'NotAllowedError') {
                        notify('error', 'Microphone permission was denied.');
                        return;
                    }

                    notify('error', 'No microphone detected. Please connect a microphone to continue.');
                }
            }

            function sendRecordedVoice() {
                const context = getRecorderContext();

                if (context.mediaRecorder && context.mediaRecorder.state === 'recording') {
                    stopVoiceRecording(true);
                    return;
                }

                if (!context.$voiceRecorderInput?.[0]?.files.length) {
                    notify('error', 'Please record and stop the audio before sending.');
                    return;
                }

                context.hasRecordedPreview = false;
                hideVoiceRecorderPanel();
                updateRecorderUiState();
                setTimeout(() => {
                    context.$messageForm.trigger('submit');
                }, 150);
            }

            window.openVoiceRecorderPanel = openVoiceRecorderPanel;
            window.startVoiceRecording = startVoiceRecording;
            window.stopVoiceRecording = stopVoiceRecording;
            window.sendRecordedVoice = sendRecordedVoice;
            window.cancelVoiceRecording = cancelVoiceRecording;
        })(jQuery);
    </script>
@endpush