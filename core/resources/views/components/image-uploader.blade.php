@props([
    'type' => null,
    'image' => null,
    'imagePath' => null,
    'size' => null,
    'name' => 'image',
    'id' => 'image-upload-input1',
    'accept' => '.png, .jpg, .jpeg',
    'required' => true,
    'theme' => '',
])

@php
    $size = $size ?? getFileSize($type);
    if ($image) {
        $imagePath = getImage(getFilePath($type) . '/' . $image, getFileSize($type));
    }

@endphp

<div class="image-uploader cursor-pointer">
    <div class="image-upload">
        <div class="image-upload__placeholder {{ $theme }} ">
            <input type="file" class="d-none image-upload-input" name="{{ $name }}" id="{{ $id }}"
                accept="{{ $accept }}" @required($required)>
            <div class="image-upload__content">
                <div class="image-upload__thumb mb-2">
                    @if ($imagePath)
                        <img src="{{ $imagePath }}" alt="">
                    @else
                        <img src="{{ asset('assets/images/drag-and-drop.png') }}" alt="" class="drag-and-drop-thumb">
                    @endif
                </div>
                <div class="d-flex flex-column flex-wrap  gap-1 gap-md-0 align-items-center justify-content-center">
                    <p class="uploade-message">
                        <label class="text--primary cursor-pointer text-decoration-underline">
                            @lang('Click to Upload')
                        </label>
                        @lang('or drag and drop here')
                    </p>
                    <span class="text-muted supported-file-message">
                        @lang('Supported Files:')
                        <b>{{ $accept }}.</b>
                        @if ($size)
                            @lang('Image will be resized into') <b>{{ $size }}</b>@lang('px')
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        /* Image Upload Design  Start */

        .image-upload__placeholder {
            width: 100%;
            height: 280px;
            display: block;
            position: relative;
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            border: 3px solid #f1f1f1;
            padding: 12px;
            margin: 0 auto;
            border-radius: 8px;
            background-color: #F1F2F4;
        }

        [data-theme=dark] .image-upload__placeholder {
            background-color: #F1F2F4;
        }

        .image-upload__text {
            width: 100%;
            text-align: center;
            display: none;
        }

        .image-upload__icon {
            --icon-size: 48px;
            position: absolute;
            bottom: -20px;
            right: -24px;
            width: var(--icon-size);
            height: var(--icon-size);
            border-radius: 50%;
            background: hsl(var(--primary));
            color: hsl(var(--white));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            border: 4px solid hsl(var(--white));
        }

        .image-uploader .image-upload {
            padding: 8px;
            background: hsl(var(--white));
            border-radius: 8px;
            border: 3px dashed hsl(var(--black) / 0.2);
        }

        @media (max-width: 1499px) {
            .image-uploader .image-upload__placeholder {
                height: 300px;
            }
        }

        @media (max-width: 991px) {
            .image-uploader .image-upload__placeholder {
                height: 250px;
            }
        }

        .image-upload__text {
            font-size: 0.875rem;
            margin-top: 10px;
        }

        .image-upload__content {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            height: 100%;
            padding: 10px;
        }

        .image-upload__content .uploade-message {
            font-size: 0.9375rem;
            text-align: center;
            margin-bottom: 0;
            color: hsl(var(--body-color));
        }

        .image-upload__content .supported-file-message {
            font-size: 0.8125rem;
            text-align: center;
            display: block;
        }

        .image-upload__thumb {
            max-width: 80%;
            max-height: 80%;
            text-align: center
        }


        @media screen and (max-width: 575px) {
            .image-upload__thumb {
                max-width: 70%;
                max-height: 70%;
            }

            .image-upload__content .uploade-message {
                font-size: 0.8125rem;
            }

        }

        @media screen and (max-width: 425px) {
            .image-upload__thumb {
                max-width: 50%;
                max-height: 50%;
            }
        }

        @media screen and (max-width:1450px) {
            .image-upload__placeholder {
                padding: 10px;
            }
        }

        @media screen and (max-width:425px) {
            .image-upload__placeholder {
                padding: 8px;
            }
        }

        @media screen and (max-width:375px) {
            .image-upload__content {
                padding: 0px;
            }
        }

        @media screen and (max-width:768px) {
            .image-uploader .image-upload {
                padding: 5px;
            }
        }

        .image-upload__thumb img {
            max-height: 100%;
            max-width: 100%;
            border-radius: 5px;
        }

        [data-theme=dark] .image-upload__placeholder:not(.light),
        .image-upload__placeholder.dark {
            background-color: #25293c;

        }

        [data-theme=dark] .image-upload__placeholder.dark,
        [data-theme=dark] .image-upload__placeholder {
            border-color: #525770;
        }

        [data-theme=dark] .image-uploader .image-upload:has(.dark),
        [data-theme=dark] .image-uploader .image-upload {
            background: hsl(var(--light))
        }

        .dark .image-upload__content .uploade-message,
        .dark .image-upload__content .supported-file-message,
        [data-theme=dark] .image-upload__placeholder:not(.light) .image-upload__content .uploade-message,
        [data-theme=dark] .image-upload__placeholder:not(.light) .image-upload__content .supported-file-message {
            color: #ffffff !important;
        }
    </style>
@endpush
