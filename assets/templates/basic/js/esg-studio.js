(function () {
    "use strict";
    const emojisData = [
    {
        "id": "smileys",
        "icon": "bi-emoji-smile",
        "title": "Smileys & Emotion",
        "items": [
            {
                "char": "😀",
                "name": "grinning"
            },
            {
                "char": "😃",
                "name": "smiley"
            },
            {
                "char": "😄",
                "name": "smile"
            },
            {
                "char": "😁",
                "name": "grin"
            },
            {
                "char": "😆",
                "name": "laughing"
            },
            {
                "char": "😅",
                "name": "sweat_smile"
            },
            {
                "char": "🤣",
                "name": "rofl"
            },
            {
                "char": "😂",
                "name": "joy"
            },
            {
                "char": "🙂",
                "name": "slightly_smiling_face"
            },
            {
                "char": "🙃",
                "name": "upside_down_face"
            },
            {
                "char": "😉",
                "name": "wink"
            },
            {
                "char": "😊",
                "name": "blush"
            },
            {
                "char": "😇",
                "name": "innocent"
            },
            {
                "char": "🥰",
                "name": "smiling_face_with_three_hearts"
            },
            {
                "char": "😍",
                "name": "heart_eyes"
            },
            {
                "char": "🤩",
                "name": "star_struck"
            },
            {
                "char": "😘",
                "name": "kissing_heart"
            },
            {
                "char": "😗",
                "name": "kissing"
            },
            {
                "char": "☺️",
                "name": "relaxed"
            },
            {
                "char": "😚",
                "name": "kissing_closed_eyes"
            },
            {
                "char": "😙",
                "name": "kissing_smiling_eyes"
            },

            {
                "char": "😋",
                "name": "yum"
            },
            {
                "char": "😛",
                "name": "stuck_out_tongue"
            },
            {
                "char": "😜",
                "name": "stuck_out_tongue_winking_eye"
            },
            {
                "char": "🤪",
                "name": "zany_face"
            },
            {
                "char": "😝",
                "name": "stuck_out_tongue_closed_eyes"
            },
            {
                "char": "🤑",
                "name": "money_mouth_face"
            },
            {
                "char": "🤗",
                "name": "hugs"
            },
            {
                "char": "🤭",
                "name": "hand_over_mouth"
            },

            {
                "char": "🤫",
                "name": "shushing_face"
            },
            {
                "char": "🤔",
                "name": "thinking"
            },

            {
                "char": "🤐",
                "name": "zipper_mouth_face"
            },
            {
                "char": "🤨",
                "name": "raised_eyebrow"
            },
            {
                "char": "😐",
                "name": "neutral_face"
            },
            {
                "char": "😑",
                "name": "expressionless"
            },
            {
                "char": "😶",
                "name": "no_mouth"
            },
            {
                "char": "😶‍🌫️",
                "name": "face_in_clouds"
            },
            {
                "char": "😏",
                "name": "smirk"
            },
            {
                "char": "😒",
                "name": "unamused"
            },
            {
                "char": "🙄",
                "name": "roll_eyes"
            },
            {
                "char": "😬",
                "name": "grimacing"
            },
            {
                "char": "😮‍💨",
                "name": "face_exhaling"
            },
            {
                "char": "🤥",
                "name": "lying_face"
            },
            {
                "char": "😌",
                "name": "relieved"
            },
            {
                "char": "😔",
                "name": "pensive"
            },
            {
                "char": "😪",
                "name": "sleepy"
            },
            {
                "char": "🤤",
                "name": "drooling_face"
            },
            {
                "char": "😴",
                "name": "sleeping"
            },
            {
                "char": "😷",
                "name": "mask"
            },
            {
                "char": "🤒",
                "name": "face_with_thermometer"
            },
            {
                "char": "🤕",
                "name": "face_with_head_bandage"
            },
            {
                "char": "🤢",
                "name": "nauseated_face"
            },
            {
                "char": "🤮",
                "name": "vomiting_face"
            },
            {
                "char": "🤧",
                "name": "sneezing_face"
            },
            {
                "char": "🥵",
                "name": "hot_face"
            },
            {
                "char": "🥶",
                "name": "cold_face"
            },
            {
                "char": "🥴",
                "name": "woozy_face"
            },
            {
                "char": "😵",
                "name": "dizzy_face"
            },
            {
                "char": "😵‍💫",
                "name": "face_with_spiral_eyes"
            },
            {
                "char": "🤯",
                "name": "exploding_head"
            },
            {
                "char": "🤠",
                "name": "cowboy_hat_face"
            },
            {
                "char": "🥳",
                "name": "partying_face"
            },

            {
                "char": "😎",
                "name": "sunglasses"
            },
            {
                "char": "🤓",
                "name": "nerd_face"
            },
            {
                "char": "🧐",
                "name": "monocle_face"
            },
            {
                "char": "😕",
                "name": "confused"
            },

            {
                "char": "😟",
                "name": "worried"
            },
            {
                "char": "🙁",
                "name": "slightly_frowning_face"
            },
            {
                "char": "☹️",
                "name": "frowning_face"
            },
            {
                "char": "😮",
                "name": "open_mouth"
            },
            {
                "char": "😯",
                "name": "hushed"
            },
            {
                "char": "😲",
                "name": "astonished"
            },
            {
                "char": "😳",
                "name": "flushed"
            },
            {
                "char": "🥺",
                "name": "pleading_face"
            },

            {
                "char": "😦",
                "name": "frowning"
            },
            {
                "char": "😧",
                "name": "anguished"
            },
            {
                "char": "😨",
                "name": "fearful"
            },
            {
                "char": "😰",
                "name": "cold_sweat"
            },
            {
                "char": "😥",
                "name": "disappointed_relieved"
            },
            {
                "char": "😢",
                "name": "cry"
            },
            {
                "char": "😭",
                "name": "sob"
            },
            {
                "char": "😱",
                "name": "scream"
            },
            {
                "char": "😖",
                "name": "confounded"
            },
            {
                "char": "😣",
                "name": "persevere"
            },
            {
                "char": "😞",
                "name": "disappointed"
            },
            {
                "char": "😓",
                "name": "sweat"
            },
            {
                "char": "😩",
                "name": "weary"
            },
            {
                "char": "😫",
                "name": "tired_face"
            },
            {
                "char": "🥱",
                "name": "yawning_face"
            },
            {
                "char": "😤",
                "name": "triumph"
            },
            {
                "char": "😡",
                "name": "rage"
            },
            {
                "char": "😠",
                "name": "angry"
            },
            {
                "char": "🤬",
                "name": "cursing_face"
            },
            {
                "char": "😈",
                "name": "smiling_imp"
            },
            {
                "char": "👿",
                "name": "imp"
            },
            {
                "char": "💀",
                "name": "skull"
            },
            {
                "char": "☠️",
                "name": "skull_and_crossbones"
            },
            {
                "char": "💩",
                "name": "hankey"
            },
            {
                "char": "🤡",
                "name": "clown_face"
            },
            {
                "char": "👹",
                "name": "japanese_ogre"
            },
            {
                "char": "👺",
                "name": "japanese_goblin"
            },
            {
                "char": "👻",
                "name": "ghost"
            },
            {
                "char": "👽",
                "name": "alien"
            },
            {
                "char": "👾",
                "name": "space_invader"
            },
            {
                "char": "🤖",
                "name": "robot"
            },
            {
                "char": "😺",
                "name": "smiley_cat"
            },
            {
                "char": "😸",
                "name": "smile_cat"
            },
            {
                "char": "😹",
                "name": "joy_cat"
            },
            {
                "char": "😻",
                "name": "heart_eyes_cat"
            },
            {
                "char": "😼",
                "name": "smirk_cat"
            },
            {
                "char": "😽",
                "name": "kissing_cat"
            },
            {
                "char": "🙀",
                "name": "scream_cat"
            },
            {
                "char": "😿",
                "name": "crying_cat_face"
            },
            {
                "char": "😾",
                "name": "pouting_cat"
            },
            {
                "char": "🙈",
                "name": "see_no_evil"
            },
            {
                "char": "🙉",
                "name": "hear_no_evil"
            },
            {
                "char": "🙊",
                "name": "speak_no_evil"
            },
            {
                "char": "💌",
                "name": "love_letter"
            },
            {
                "char": "💘",
                "name": "cupid"
            },
            {
                "char": "💝",
                "name": "gift_heart"
            },
            {
                "char": "💖",
                "name": "sparkling_heart"
            },
            {
                "char": "💗",
                "name": "heartpulse"
            },
            {
                "char": "💓",
                "name": "heartbeat"
            },
            {
                "char": "💞",
                "name": "revolving_hearts"
            },
            {
                "char": "💕",
                "name": "two_hearts"
            },
            {
                "char": "💟",
                "name": "heart_decoration"
            },
            {
                "char": "❣️",
                "name": "heavy_heart_exclamation"
            },
            {
                "char": "💔",
                "name": "broken_heart"
            },
            {
                "char": "❤️‍🔥",
                "name": "heart_on_fire"
            },
            {
                "char": "❤️‍🩹",
                "name": "mending_heart"
            },
            {
                "char": "❤️",
                "name": "heart"
            },
            {
                "char": "🧡",
                "name": "orange_heart"
            },
            {
                "char": "💛",
                "name": "yellow_heart"
            },
            {
                "char": "💚",
                "name": "green_heart"
            },
            {
                "char": "💙",
                "name": "blue_heart"
            },

            {
                "char": "💜",
                "name": "purple_heart"
            },
            {
                "char": "🤎",
                "name": "brown_heart"
            },
            {
                "char": "🖤",
                "name": "black_heart"
            },
            {
                "char": "🩶",
                "name": "grey_heart"
            },
            {
                "char": "🤍",
                "name": "white_heart"
            },
            {
                "char": "💋",
                "name": "kiss"
            },
            {
                "char": "💯",
                "name": "100"
            },
            {
                "char": "💢",
                "name": "anger"
            },
            {
                "char": "💥",
                "name": "boom"
            },
            {
                "char": "💫",
                "name": "dizzy"
            },
            {
                "char": "💦",
                "name": "sweat_drops"
            },
            {
                "char": "💨",
                "name": "dash"
            },
            {
                "char": "🕳️",
                "name": "hole"
            },
            {
                "char": "💬",
                "name": "speech_balloon"
            },
            {
                "char": "👁️‍🗨️",
                "name": "eye_speech_bubble"
            },
            {
                "char": "🗨️",
                "name": "left_speech_bubble"
            },
            {
                "char": "🗯️",
                "name": "right_anger_bubble"
            },
            {
                "char": "💭",
                "name": "thought_balloon"
            },
            {
                "char": "💤",
                "name": "zzz"
            }
        ]
    },
    {
        "id": "people",
        "icon": "bi-person",
        "title": "People & Body",
        "items": [
            {
                "char": "👋",
                "name": "wave"
            },
            {
                "char": "🤚",
                "name": "raised_back_of_hand"
            },
            {
                "char": "🖐️",
                "name": "raised_hand_with_fingers_splayed"
            },
            {
                "char": "✋",
                "name": "hand"
            },
            {
                "char": "🖖",
                "name": "vulcan_salute"
            },

            {
                "char": "👌",
                "name": "ok_hand"
            },
            {
                "char": "🤌",
                "name": "pinched_fingers"
            },
            {
                "char": "🤏",
                "name": "pinching_hand"
            },
            {
                "char": "✌️",
                "name": "v"
            },
            {
                "char": "🤞",
                "name": "crossed_fingers"
            },
        
            {
                "char": "🤟",
                "name": "love_you_gesture"
            },
            {
                "char": "🤘",
                "name": "metal"
            },
            {
                "char": "🤙",
                "name": "call_me_hand"
            },
            {
                "char": "👈",
                "name": "point_left"
            },
            {
                "char": "👉",
                "name": "point_right"
            },
            {
                "char": "👆",
                "name": "point_up_2"
            },
            {
                "char": "🖕",
                "name": "middle_finger"
            },
            {
                "char": "👇",
                "name": "point_down"
            },
            {
                "char": "☝️",
                "name": "point_up"
            },
         
            {
                "char": "👍",
                "name": "+1"
            },
            {
                "char": "👎",
                "name": "-1"
            },
            {
                "char": "✊",
                "name": "fist_raised"
            },
            {
                "char": "👊",
                "name": "fist_oncoming"
            },
            {
                "char": "🤛",
                "name": "fist_left"
            },
            {
                "char": "🤜",
                "name": "fist_right"
            },
            {
                "char": "👏",
                "name": "clap"
            },
            {
                "char": "🙌",
                "name": "raised_hands"
            },
            {
                "char": "👐",
                "name": "open_hands"
            },
            {
                "char": "🤲",
                "name": "palms_up_together"
            },
            {
                "char": "🤝",
                "name": "handshake"
            },
            {
                "char": "🙏",
                "name": "pray"
            },
            {
                "char": "✍️",
                "name": "writing_hand"
            },
            {
                "char": "💅",
                "name": "nail_care"
            },
            {
                "char": "🤳",
                "name": "selfie"
            },
            {
                "char": "💪",
                "name": "muscle"
            },
            {
                "char": "🦾",
                "name": "mechanical_arm"
            },
            {
                "char": "🦿",
                "name": "mechanical_leg"
            },
            {
                "char": "🦵",
                "name": "leg"
            },
            {
                "char": "🦶",
                "name": "foot"
            },
            {
                "char": "👂",
                "name": "ear"
            },
            {
                "char": "🦻",
                "name": "ear_with_hearing_aid"
            },
            {
                "char": "👃",
                "name": "nose"
            },
            {
                "char": "🧠",
                "name": "brain"
            },

            {
                "char": "🦷",
                "name": "tooth"
            },
            {
                "char": "🦴",
                "name": "bone"
            },
            {
                "char": "👀",
                "name": "eyes"
            },
            {
                "char": "👁️",
                "name": "eye"
            },
            {
                "char": "👅",
                "name": "tongue"
            },
            {
                "char": "👄",
                "name": "lips"
            },
            {
                "char": "👶",
                "name": "baby"
            },
            {
                "char": "🧒",
                "name": "child"
            },
            {
                "char": "👦",
                "name": "boy"
            },
            {
                "char": "👧",
                "name": "girl"
            },
            {
                "char": "🧑",
                "name": "adult"
            },
            {
                "char": "👱",
                "name": "blond_haired_person"
            },
            {
                "char": "👨",
                "name": "man"
            },
            {
                "char": "🧔",
                "name": "bearded_person"
            },
            {
                "char": "🧔‍♂️",
                "name": "man_beard"
            },
            {
                "char": "🧔‍♀️",
                "name": "woman_beard"
            },
            {
                "char": "👨‍🦰",
                "name": "red_haired_man"
            },
            {
                "char": "👨‍🦱",
                "name": "curly_haired_man"
            },
            {
                "char": "👨‍🦳",
                "name": "white_haired_man"
            },
            {
                "char": "👨‍🦲",
                "name": "bald_man"
            },
            {
                "char": "👩",
                "name": "woman"
            },
            {
                "char": "👩‍🦰",
                "name": "red_haired_woman"
            },
            {
                "char": "🧑‍🦰",
                "name": "person_red_hair"
            },
            {
                "char": "👩‍🦱",
                "name": "curly_haired_woman"
            },
            {
                "char": "🧑‍🦱",
                "name": "person_curly_hair"
            },
            {
                "char": "👩‍🦳",
                "name": "white_haired_woman"
            },
            {
                "char": "🧑‍🦳",
                "name": "person_white_hair"
            },
            {
                "char": "👩‍🦲",
                "name": "bald_woman"
            },
            {
                "char": "🧑‍🦲",
                "name": "person_bald"
            },
            {
                "char": "👱‍♀️",
                "name": "blond_haired_woman"
            },
            {
                "char": "👱‍♂️",
                "name": "blond_haired_man"
            },
            {
                "char": "🧓",
                "name": "older_adult"
            },
            {
                "char": "👴",
                "name": "older_man"
            },
            {
                "char": "👵",
                "name": "older_woman"
            },
            {
                "char": "🙍",
                "name": "frowning_person"
            },
            {
                "char": "🙍‍♂️",
                "name": "frowning_man"
            },
            {
                "char": "🙍‍♀️",
                "name": "frowning_woman"
            },
            {
                "char": "🙎",
                "name": "pouting_face"
            },
            {
                "char": "🙎‍♂️",
                "name": "pouting_man"
            },
            {
                "char": "🙎‍♀️",
                "name": "pouting_woman"
            },
            {
                "char": "🙅",
                "name": "no_good"
            },
            {
                "char": "🙅‍♂️",
                "name": "no_good_man"
            },
            {
                "char": "🙅‍♀️",
                "name": "no_good_woman"
            },
            {
                "char": "🙆",
                "name": "ok_person"
            },
            {
                "char": "🙆‍♂️",
                "name": "ok_man"
            },
            {
                "char": "🙆‍♀️",
                "name": "ok_woman"
            },
            {
                "char": "💁",
                "name": "tipping_hand_person"
            },
            {
                "char": "💁‍♂️",
                "name": "tipping_hand_man"
            },
            {
                "char": "💁‍♀️",
                "name": "tipping_hand_woman"
            },
            {
                "char": "🙋",
                "name": "raising_hand"
            },
            {
                "char": "🙋‍♂️",
                "name": "raising_hand_man"
            },
            {
                "char": "🙋‍♀️",
                "name": "raising_hand_woman"
            },
            {
                "char": "🧏",
                "name": "deaf_person"
            },
            {
                "char": "🧏‍♂️",
                "name": "deaf_man"
            },
            {
                "char": "🧏‍♀️",
                "name": "deaf_woman"
            },
            {
                "char": "🙇",
                "name": "bow"
            },
            {
                "char": "🙇‍♂️",
                "name": "bowing_man"
            },
            {
                "char": "🙇‍♀️",
                "name": "bowing_woman"
            },
            {
                "char": "🤦",
                "name": "facepalm"
            },
            {
                "char": "🤦‍♂️",
                "name": "man_facepalming"
            },
            {
                "char": "🤦‍♀️",
                "name": "woman_facepalming"
            },
            {
                "char": "🤷",
                "name": "shrug"
            },
            {
                "char": "🤷‍♂️",
                "name": "man_shrugging"
            },
            {
                "char": "🤷‍♀️",
                "name": "woman_shrugging"
            },
            {
                "char": "🧑‍⚕️",
                "name": "health_worker"
            },
            {
                "char": "👨‍⚕️",
                "name": "man_health_worker"
            },
            {
                "char": "👩‍⚕️",
                "name": "woman_health_worker"
            },
            {
                "char": "🧑‍🎓",
                "name": "student"
            },
            {
                "char": "👨‍🎓",
                "name": "man_student"
            },
            {
                "char": "👩‍🎓",
                "name": "woman_student"
            },
            {
                "char": "🧑‍🏫",
                "name": "teacher"
            },
            {
                "char": "👨‍🏫",
                "name": "man_teacher"
            },
            {
                "char": "👩‍🏫",
                "name": "woman_teacher"
            },
            {
                "char": "🧑‍⚖️",
                "name": "judge"
            },
            {
                "char": "👨‍⚖️",
                "name": "man_judge"
            },
            {
                "char": "👩‍⚖️",
                "name": "woman_judge"
            },
            {
                "char": "🧑‍🌾",
                "name": "farmer"
            },
            {
                "char": "👨‍🌾",
                "name": "man_farmer"
            },
            {
                "char": "👩‍🌾",
                "name": "woman_farmer"
            },
            {
                "char": "🧑‍🍳",
                "name": "cook"
            },
            {
                "char": "👨‍🍳",
                "name": "man_cook"
            },
            {
                "char": "👩‍🍳",
                "name": "woman_cook"
            },
            {
                "char": "🧑‍🔧",
                "name": "mechanic"
            },
            {
                "char": "👨‍🔧",
                "name": "man_mechanic"
            },
            {
                "char": "👩‍🔧",
                "name": "woman_mechanic"
            },
            {
                "char": "🧑‍🏭",
                "name": "factory_worker"
            },
            {
                "char": "👨‍🏭",
                "name": "man_factory_worker"
            },
            {
                "char": "👩‍🏭",
                "name": "woman_factory_worker"
            },
            {
                "char": "🧑‍💼",
                "name": "office_worker"
            },
            {
                "char": "👨‍💼",
                "name": "man_office_worker"
            },
            {
                "char": "👩‍💼",
                "name": "woman_office_worker"
            },
            {
                "char": "🧑‍🔬",
                "name": "scientist"
            },
            {
                "char": "👨‍🔬",
                "name": "man_scientist"
            },
            {
                "char": "👩‍🔬",
                "name": "woman_scientist"
            },
            {
                "char": "🧑‍💻",
                "name": "technologist"
            },
            {
                "char": "👨‍💻",
                "name": "man_technologist"
            },
            {
                "char": "👩‍💻",
                "name": "woman_technologist"
            },
            {
                "char": "🧑‍🎤",
                "name": "singer"
            },
            {
                "char": "👨‍🎤",
                "name": "man_singer"
            },
            {
                "char": "👩‍🎤",
                "name": "woman_singer"
            },
            {
                "char": "🧑‍🎨",
                "name": "artist"
            },
            {
                "char": "👨‍🎨",
                "name": "man_artist"
            },
            {
                "char": "👩‍🎨",
                "name": "woman_artist"
            },
            {
                "char": "🧑‍✈️",
                "name": "pilot"
            },
            {
                "char": "👨‍✈️",
                "name": "man_pilot"
            },
            {
                "char": "👩‍✈️",
                "name": "woman_pilot"
            },
            {
                "char": "🧑‍🚀",
                "name": "astronaut"
            },
            {
                "char": "👨‍🚀",
                "name": "man_astronaut"
            },
            {
                "char": "👩‍🚀",
                "name": "woman_astronaut"
            },
            {
                "char": "🧑‍🚒",
                "name": "firefighter"
            },
            {
                "char": "👨‍🚒",
                "name": "man_firefighter"
            },
            {
                "char": "👩‍🚒",
                "name": "woman_firefighter"
            },
            {
                "char": "👮",
                "name": "police_officer"
            },
            {
                "char": "👮‍♂️",
                "name": "policeman"
            },
            {
                "char": "👮‍♀️",
                "name": "policewoman"
            },
            {
                "char": "🕵️",
                "name": "detective"
            },
            {
                "char": "🕵️‍♂️",
                "name": "male_detective"
            },
            {
                "char": "🕵️‍♀️",
                "name": "female_detective"
            },
            {
                "char": "💂",
                "name": "guard"
            },
            {
                "char": "💂‍♂️",
                "name": "guardsman"
            },
            {
                "char": "💂‍♀️",
                "name": "guardswoman"
            },
        
            {
                "char": "👷",
                "name": "construction_worker"
            },
            {
                "char": "👷‍♂️",
                "name": "construction_worker_man"
            },
            {
                "char": "👷‍♀️",
                "name": "construction_worker_woman"
            },

            {
                "char": "🤴",
                "name": "prince"
            },
            {
                "char": "👸",
                "name": "princess"
            },
            {
                "char": "👳",
                "name": "person_with_turban"
            },
            {
                "char": "👳‍♂️",
                "name": "man_with_turban"
            },
            {
                "char": "👳‍♀️",
                "name": "woman_with_turban"
            },
            {
                "char": "👲",
                "name": "man_with_gua_pi_mao"
            },
            {
                "char": "🧕",
                "name": "woman_with_headscarf"
            },
            {
                "char": "🤵",
                "name": "person_in_tuxedo"
            },
            {
                "char": "🤵‍♂️",
                "name": "man_in_tuxedo"
            },
            {
                "char": "🤵‍♀️",
                "name": "woman_in_tuxedo"
            },
            {
                "char": "👰",
                "name": "person_with_veil"
            },
            {
                "char": "👰‍♂️",
                "name": "man_with_veil"
            },
            {
                "char": "👰‍♀️",
                "name": "woman_with_veil"
            },
            {
                "char": "🤰",
                "name": "pregnant_woman"
            },
            {
                "char": "🤰",
                "name": "pregnant_man"
            },
            {
                "char": "🤱",
                "name": "breast_feeding"
            },
            {
                "char": "👩‍🍼",
                "name": "woman_feeding_baby"
            },
            {
                "char": "👨‍🍼",
                "name": "man_feeding_baby"
            },
            {
                "char": "🧑‍🍼",
                "name": "person_feeding_baby"
            },
            {
                "char": "👼",
                "name": "angel"
            },
            {
                "char": "🎅",
                "name": "santa"
            },
            {
                "char": "🤶",
                "name": "mrs_claus"
            },
            {
                "char": "🧑‍🎄",
                "name": "mx_claus"
            },
            {
                "char": "🦸",
                "name": "superhero"
            },
            {
                "char": "🦸‍♂️",
                "name": "superhero_man"
            },
            {
                "char": "🦸‍♀️",
                "name": "superhero_woman"
            },
            {
                "char": "🦹",
                "name": "supervillain"
            },
            {
                "char": "🦹‍♂️",
                "name": "supervillain_man"
            },
            {
                "char": "🦹‍♀️",
                "name": "supervillain_woman"
            },
            {
                "char": "🧙",
                "name": "mage"
            },
            {
                "char": "🧙‍♂️",
                "name": "mage_man"
            },
            {
                "char": "🧙‍♀️",
                "name": "mage_woman"
            },
            {
                "char": "🧚",
                "name": "fairy"
            },
            {
                "char": "🧚‍♂️",
                "name": "fairy_man"
            },
            {
                "char": "🧚‍♀️",
                "name": "fairy_woman"
            },
            {
                "char": "🧛",
                "name": "vampire"
            },
            {
                "char": "🧛‍♂️",
                "name": "vampire_man"
            },
            {
                "char": "🧛‍♀️",
                "name": "vampire_woman"
            },
            {
                "char": "🧜",
                "name": "merperson"
            },
            {
                "char": "🧜‍♂️",
                "name": "merman"
            },
            {
                "char": "🧜‍♀️",
                "name": "mermaid"
            },
            {
                "char": "🧝",
                "name": "elf"
            },
            {
                "char": "🧝‍♂️",
                "name": "elf_man"
            },
            {
                "char": "🧝‍♀️",
                "name": "elf_woman"
            },
            {
                "char": "🧞",
                "name": "genie"
            },
            {
                "char": "🧞‍♂️",
                "name": "genie_man"
            },
            {
                "char": "🧞‍♀️",
                "name": "genie_woman"
            },
            {
                "char": "🧟",
                "name": "zombie"
            },
            {
                "char": "🧟‍♂️",
                "name": "zombie_man"
            },
            {
                "char": "🧟‍♀️",
                "name": "zombie_woman"
            },
            {
                "char": "🚋",
                "name": "troll"
            },
            {
                "char": "💆",
                "name": "massage"
            },
            {
                "char": "💆‍♂️",
                "name": "massage_man"
            },
            {
                "char": "💆‍♀️",
                "name": "massage_woman"
            },
            {
                "char": "💇",
                "name": "haircut"
            },
            {
                "char": "💇‍♂️",
                "name": "haircut_man"
            },
            {
                "char": "💇‍♀️",
                "name": "haircut_woman"
            },
            {
                "char": "🚶",
                "name": "walking"
            },
            {
                "char": "🚶‍♂️",
                "name": "walking_man"
            },
            {
                "char": "🚶‍♀️",
                "name": "walking_woman"
            },
            {
                "char": "🧍",
                "name": "standing_person"
            },
            {
                "char": "🧍‍♂️",
                "name": "standing_man"
            },
            {
                "char": "🧍‍♀️",
                "name": "standing_woman"
            },
            {
                "char": "🧎",
                "name": "kneeling_person"
            },
            {
                "char": "🧎‍♂️",
                "name": "kneeling_man"
            },
            {
                "char": "🧎‍♀️",
                "name": "kneeling_woman"
            },
            {
                "char": "🧑‍🦯",
                "name": "person_with_probing_cane"
            },
            {
                "char": "👨‍🦯",
                "name": "man_with_probing_cane"
            },
            {
                "char": "👩‍🦯",
                "name": "woman_with_probing_cane"
            },
            {
                "char": "🧑‍🦼",
                "name": "person_in_motorized_wheelchair"
            },
            {
                "char": "👨‍🦼",
                "name": "man_in_motorized_wheelchair"
            },
            {
                "char": "👩‍🦼",
                "name": "woman_in_motorized_wheelchair"
            },
            {
                "char": "🧑‍🦽",
                "name": "person_in_manual_wheelchair"
            },
            {
                "char": "👨‍🦽",
                "name": "man_in_manual_wheelchair"
            },
            {
                "char": "👩‍🦽",
                "name": "woman_in_manual_wheelchair"
            },
            {
                "char": "🏃",
                "name": "runner"
            },
            {
                "char": "🏃‍♂️",
                "name": "running_man"
            },
            {
                "char": "🏃‍♀️",
                "name": "running_woman"
            },
            {
                "char": "💃",
                "name": "woman_dancing"
            },
            {
                "char": "🕺",
                "name": "man_dancing"
            },
            {
                "char": "🕴️",
                "name": "business_suit_levitating"
            },
            {
                "char": "👯",
                "name": "dancers"
            },
            {
                "char": "👯‍♂️",
                "name": "dancing_men"
            },
            {
                "char": "👯‍♀️",
                "name": "dancing_women"
            },
            {
                "char": "🧖",
                "name": "sauna_person"
            },
            {
                "char": "🧖‍♂️",
                "name": "sauna_man"
            },
            {
                "char": "🧖‍♀️",
                "name": "sauna_woman"
            },
            {
                "char": "🧗",
                "name": "climbing"
            },
            {
                "char": "🧗‍♂️",
                "name": "climbing_man"
            },
            {
                "char": "🧗‍♀️",
                "name": "climbing_woman"
            },
            {
                "char": "🤺",
                "name": "person_fencing"
            },
            {
                "char": "🏇",
                "name": "horse_racing"
            },
            {
                "char": "⛷️",
                "name": "skier"
            },
            {
                "char": "🏂",
                "name": "snowboarder"
            },
            {
                "char": "🏌️",
                "name": "golfing"
            },
            {
                "char": "🏌️‍♂️",
                "name": "golfing_man"
            },
            {
                "char": "🏌️‍♀️",
                "name": "golfing_woman"
            },
            {
                "char": "🏄",
                "name": "surfer"
            },
            {
                "char": "🏄‍♂️",
                "name": "surfing_man"
            },
            {
                "char": "🏄‍♀️",
                "name": "surfing_woman"
            },
            {
                "char": "🚣",
                "name": "rowboat"
            },
            {
                "char": "🚣‍♂️",
                "name": "rowing_man"
            },
            {
                "char": "🚣‍♀️",
                "name": "rowing_woman"
            },
            {
                "char": "🏊",
                "name": "swimmer"
            },
            {
                "char": "🏊‍♂️",
                "name": "swimming_man"
            },
            {
                "char": "🏊‍♀️",
                "name": "swimming_woman"
            },
            {
                "char": "⛹️",
                "name": "bouncing_ball_person"
            },
            {
                "char": "⛹️‍♂️",
                "name": "bouncing_ball_man"
            },
            {
                "char": "⛹️‍♀️",
                "name": "bouncing_ball_woman"
            },
            {
                "char": "🏋️",
                "name": "weight_lifting"
            },
            {
                "char": "🏋️‍♂️",
                "name": "weight_lifting_man"
            },
            {
                "char": "🏋️‍♀️",
                "name": "weight_lifting_woman"
            },
            {
                "char": "🚴",
                "name": "bicyclist"
            },
            {
                "char": "🚴‍♂️",
                "name": "biking_man"
            },
            {
                "char": "🚴‍♀️",
                "name": "biking_woman"
            },
            {
                "char": "🚵",
                "name": "mountain_bicyclist"
            },
            {
                "char": "🚵‍♂️",
                "name": "mountain_biking_man"
            },
            {
                "char": "🚵‍♀️",
                "name": "mountain_biking_woman"
            },
            {
                "char": "🤸",
                "name": "cartwheeling"
            },
            {
                "char": "🤸‍♂️",
                "name": "man_cartwheeling"
            },
            {
                "char": "🤸‍♀️",
                "name": "woman_cartwheeling"
            },
            {
                "char": "🤼",
                "name": "wrestling"
            },
            {
                "char": "🤼‍♂️",
                "name": "men_wrestling"
            },
            {
                "char": "🤼‍♀️",
                "name": "women_wrestling"
            },
            {
                "char": "🤽",
                "name": "water_polo"
            },
            {
                "char": "🤽‍♂️",
                "name": "man_playing_water_polo"
            },
            {
                "char": "🤽‍♀️",
                "name": "woman_playing_water_polo"
            },
            {
                "char": "🤾",
                "name": "handball_person"
            },
            {
                "char": "🤾‍♂️",
                "name": "man_playing_handball"
            },
            {
                "char": "🤾‍♀️",
                "name": "woman_playing_handball"
            },
            {
                "char": "🤹",
                "name": "juggling_person"
            },
            {
                "char": "🤹‍♂️",
                "name": "man_juggling"
            },
            {
                "char": "🤹‍♀️",
                "name": "woman_juggling"
            },
            {
                "char": "🧘",
                "name": "lotus_position"
            },
            {
                "char": "🧘‍♂️",
                "name": "lotus_position_man"
            },
            {
                "char": "🧘‍♀️",
                "name": "lotus_position_woman"
            },
            {
                "char": "🛀",
                "name": "bath"
            },
            {
                "char": "🛌",
                "name": "sleeping_bed"
            },
            {
                "char": "🧑‍🤝‍🧑",
                "name": "people_holding_hands"
            },
            {
                "char": "👭",
                "name": "two_women_holding_hands"
            },
            {
                "char": "👫",
                "name": "couple"
            },
            {
                "char": "👬",
                "name": "two_men_holding_hands"
            },
            {
                "char": "💏",
                "name": "couplekiss"
            },
            {
                "char": "👩‍❤️‍💋‍👨",
                "name": "couplekiss_man_woman"
            },
            {
                "char": "👨‍❤️‍💋‍👨",
                "name": "couplekiss_man_man"
            },
            {
                "char": "👩‍❤️‍💋‍👩",
                "name": "couplekiss_woman_woman"
            },
            {
                "char": "💑",
                "name": "couple_with_heart"
            },
            {
                "char": "👩‍❤️‍👨",
                "name": "couple_with_heart_woman_man"
            },
            {
                "char": "👨‍❤️‍👨",
                "name": "couple_with_heart_man_man"
            },
            {
                "char": "👩‍❤️‍👩",
                "name": "couple_with_heart_woman_woman"
            },
            {
                "char": "👪",
                "name": "family"
            },
            {
                "char": "👨‍👩‍👦",
                "name": "family_man_woman_boy"
            },
            {
                "char": "👨‍👩‍👧",
                "name": "family_man_woman_girl"
            },
            {
                "char": "👨‍👩‍👧‍👦",
                "name": "family_man_woman_girl_boy"
            },
            {
                "char": "👨‍👩‍👦‍👦",
                "name": "family_man_woman_boy_boy"
            },
            {
                "char": "👨‍👩‍👧‍👧",
                "name": "family_man_woman_girl_girl"
            },
            {
                "char": "👨‍👨‍👦",
                "name": "family_man_man_boy"
            },
            {
                "char": "👨‍👨‍👧",
                "name": "family_man_man_girl"
            },
            {
                "char": "👨‍👨‍👧‍👦",
                "name": "family_man_man_girl_boy"
            },
            {
                "char": "👨‍👨‍👦‍👦",
                "name": "family_man_man_boy_boy"
            },
            {
                "char": "👨‍👨‍👧‍👧",
                "name": "family_man_man_girl_girl"
            },
            {
                "char": "👩‍👩‍👦",
                "name": "family_woman_woman_boy"
            },
            {
                "char": "👩‍👩‍👧",
                "name": "family_woman_woman_girl"
            },
            {
                "char": "👩‍👩‍👧‍👦",
                "name": "family_woman_woman_girl_boy"
            },
            {
                "char": "👩‍👩‍👦‍👦",
                "name": "family_woman_woman_boy_boy"
            },
            {
                "char": "👩‍👩‍👧‍👧",
                "name": "family_woman_woman_girl_girl"
            },
            {
                "char": "👨‍👦",
                "name": "family_man_boy"
            },
            {
                "char": "👨‍👦‍👦",
                "name": "family_man_boy_boy"
            },
            {
                "char": "👨‍👧",
                "name": "family_man_girl"
            },
            {
                "char": "👨‍👧‍👦",
                "name": "family_man_girl_boy"
            },
            {
                "char": "👨‍👧‍👧",
                "name": "family_man_girl_girl"
            },
            {
                "char": "👩‍👦",
                "name": "family_woman_boy"
            },
            {
                "char": "👩‍👦‍👦",
                "name": "family_woman_boy_boy"
            },
            {
                "char": "👩‍👧",
                "name": "family_woman_girl"
            },
            {
                "char": "👩‍👧‍👦",
                "name": "family_woman_girl_boy"
            },
            {
                "char": "👩‍👧‍👧",
                "name": "family_woman_girl_girl"
            },
            {
                "char": "🗣️",
                "name": "speaking_head"
            },
            {
                "char": "👤",
                "name": "bust_in_silhouette"
            },
            {
                "char": "👥",
                "name": "busts_in_silhouette"
            },
            {
                "char": "🫂",
                "name": "people_hugging"
            },
            {
                "char": "👣",
                "name": "footprints"
            }
        ]
    },
    {
        "id": "animals",
        "icon": "bi-bug",
        "title": "Animals & Nature",
        "items": [
            {
                "char": "🐵",
                "name": "monkey_face"
            },
            {
                "char": "🐒",
                "name": "monkey"
            },
            {
                "char": "🦍",
                "name": "gorilla"
            },
            {
                "char": "🦧",
                "name": "orangutan"
            },
            {
                "char": "🐶",
                "name": "dog"
            },
            {
                "char": "🐕",
                "name": "dog2"
            },
            {
                "char": "🦮",
                "name": "guide_dog"
            },
            {
                "char": "🐕‍🦺",
                "name": "service_dog"
            },
            {
                "char": "🐩",
                "name": "poodle"
            },
            {
                "char": "🐺",
                "name": "wolf"
            },
            {
                "char": "🦊",
                "name": "fox_face"
            },
            {
                "char": "🦝",
                "name": "raccoon"
            },
            {
                "char": "🐱",
                "name": "cat"
            },
            {
                "char": "🐈",
                "name": "cat2"
            },
            {
                "char": "🐈‍⬛",
                "name": "black_cat"
            },
            {
                "char": "🦁",
                "name": "lion"
            },
            {
                "char": "🐯",
                "name": "tiger"
            },
            {
                "char": "🐅",
                "name": "tiger2"
            },
            {
                "char": "🐆",
                "name": "leopard"
            },
            {
                "char": "🐴",
                "name": "horse"
            },
            {
                "char": "🫎",
                "name": "moose"
            },
            {
                "char": "🫏",
                "name": "donkey"
            },
            {
                "char": "🐎",
                "name": "racehorse"
            },
            {
                "char": "🦄",
                "name": "unicorn"
            },
            {
                "char": "🦓",
                "name": "zebra"
            },
            {
                "char": "🦌",
                "name": "deer"
            },
            {
                "char": "🦬",
                "name": "bison"
            },
            {
                "char": "🐮",
                "name": "cow"
            },
            {
                "char": "🐂",
                "name": "ox"
            },
            {
                "char": "🐃",
                "name": "water_buffalo"
            },
            {
                "char": "🐄",
                "name": "cow2"
            },
            {
                "char": "🐷",
                "name": "pig"
            },
            {
                "char": "🐖",
                "name": "pig2"
            },
            {
                "char": "🐗",
                "name": "boar"
            },
            {
                "char": "🐽",
                "name": "pig_nose"
            },
            {
                "char": "🐏",
                "name": "ram"
            },
            {
                "char": "🐑",
                "name": "sheep"
            },
            {
                "char": "🐐",
                "name": "goat"
            },
            {
                "char": "🐪",
                "name": "dromedary_camel"
            },
            {
                "char": "🐫",
                "name": "camel"
            },
            {
                "char": "🦙",
                "name": "llama"
            },
            {
                "char": "🦒",
                "name": "giraffe"
            },
            {
                "char": "🐘",
                "name": "elephant"
            },
            {
                "char": "🦣",
                "name": "mammoth"
            },
            {
                "char": "🦏",
                "name": "rhinoceros"
            },
            {
                "char": "🦛",
                "name": "hippopotamus"
            },
            {
                "char": "🐭",
                "name": "mouse"
            },
            {
                "char": "🐁",
                "name": "mouse2"
            },
            {
                "char": "🐀",
                "name": "rat"
            },
            {
                "char": "🐹",
                "name": "hamster"
            },
            {
                "char": "🐰",
                "name": "rabbit"
            },
            {
                "char": "🐇",
                "name": "rabbit2"
            },
            {
                "char": "🐿️",
                "name": "chipmunk"
            },
            {
                "char": "🦫",
                "name": "beaver"
            },
            {
                "char": "🦔",
                "name": "hedgehog"
            },
            {
                "char": "🦇",
                "name": "bat"
            },
            {
                "char": "🐻",
                "name": "bear"
            },
            {
                "char": "🐻‍❄️",
                "name": "polar_bear"
            },
            {
                "char": "🐨",
                "name": "koala"
            },
            {
                "char": "🐼",
                "name": "panda_face"
            },
            {
                "char": "🦥",
                "name": "sloth"
            },
            {
                "char": "🦦",
                "name": "otter"
            },
            {
                "char": "🦨",
                "name": "skunk"
            },
            {
                "char": "🦘",
                "name": "kangaroo"
            },
            {
                "char": "🦡",
                "name": "badger"
            },
            {
                "char": "🐾",
                "name": "feet"
            },
            {
                "char": "🦃",
                "name": "turkey"
            },
            {
                "char": "🐔",
                "name": "chicken"
            },
            {
                "char": "🐓",
                "name": "rooster"
            },
            {
                "char": "🐣",
                "name": "hatching_chick"
            },
            {
                "char": "🐤",
                "name": "baby_chick"
            },
            {
                "char": "🐥",
                "name": "hatched_chick"
            },
            {
                "char": "🐦",
                "name": "bird"
            },
            {
                "char": "🐧",
                "name": "penguin"
            },
            {
                "char": "🕊️",
                "name": "dove"
            },
            {
                "char": "🦅",
                "name": "eagle"
            },
            {
                "char": "🦆",
                "name": "duck"
            },
            {
                "char": "🦢",
                "name": "swan"
            },
            {
                "char": "🦉",
                "name": "owl"
            },
            {
                "char": "🦤",
                "name": "dodo"
            },
            {
                "char": "🪶",
                "name": "feather"
            },
            {
                "char": "🦩",
                "name": "flamingo"
            },
            {
                "char": "🦚",
                "name": "peacock"
            },
            {
                "char": "🦜",
                "name": "parrot"
            },
            {
                "char": "🪽",
                "name": "wing"
            },
            {
                "char": "🐦‍⬛",
                "name": "black_bird"
            },
            {
                "char": "🪿",
                "name": "goose"
            },
            {
                "char": "🐸",
                "name": "frog"
            },
            {
                "char": "🐊",
                "name": "crocodile"
            },
            {
                "char": "🐢",
                "name": "turtle"
            },
            {
                "char": "🦎",
                "name": "lizard"
            },
            {
                "char": "🐍",
                "name": "snake"
            },
            {
                "char": "🐲",
                "name": "dragon_face"
            },
            {
                "char": "🐉",
                "name": "dragon"
            },
            {
                "char": "🦕",
                "name": "sauropod"
            },
            {
                "char": "🦖",
                "name": "t-rex"
            },
            {
                "char": "🐳",
                "name": "whale"
            },
            {
                "char": "🐋",
                "name": "whale2"
            },
            {
                "char": "🐬",
                "name": "dolphin"
            },

            {
                "char": "🐟",
                "name": "fish"
            },
            {
                "char": "🐠",
                "name": "tropical_fish"
            },
            {
                "char": "🐡",
                "name": "blowfish"
            },
            {
                "char": "🦈",
                "name": "shark"
            },
            {
                "char": "🐙",
                "name": "octopus"
            },
            {
                "char": "🐚",
                "name": "shell"
            },

            {
                "char": "🐌",
                "name": "snail"
            },
            {
                "char": "🦋",
                "name": "butterfly"
            },
            {
                "char": "🐛",
                "name": "bug"
            },
            {
                "char": "🐜",
                "name": "ant"
            },
            {
                "char": "🐝",
                "name": "bee"
            },

            {
                "char": "🐞",
                "name": "lady_beetle"
            },
            {
                "char": "🦗",
                "name": "cricket"
            },

            {
                "char": "🕷️",
                "name": "spider"
            },
            {
                "char": "🕸️",
                "name": "spider_web"
            },
            {
                "char": "🦂",
                "name": "scorpion"
            },
            {
                "char": "🦟",
                "name": "mosquito"
            },
            {
                "char": "🦠",
                "name": "microbe"
            },
            {
                "char": "💐",
                "name": "bouquet"
            },
            {
                "char": "🌸",
                "name": "cherry_blossom"
            },
            {
                "char": "💮",
                "name": "white_flower"
            },
            {
                "char": "🪷",
                "name": "lotus"
            },
            {
                "char": "🏵️",
                "name": "rosette"
            },
            {
                "char": "🌹",
                "name": "rose"
            },
            {
                "char": "🥀",
                "name": "wilted_flower"
            },
            {
                "char": "🌺",
                "name": "hibiscus"
            },
            {
                "char": "🌻",
                "name": "sunflower"
            },
            {
                "char": "🌼",
                "name": "blossom"
            },
            {
                "char": "🌷",
                "name": "tulip"
            },
            {
                "char": "🪻",
                "name": "hyacinth"
            },
            {
                "char": "🌱",
                "name": "seedling"
            },
            {
                "char": "🪴",
                "name": "potted_plant"
            },
            {
                "char": "🌲",
                "name": "evergreen_tree"
            },
            {
                "char": "🌳",
                "name": "deciduous_tree"
            },
            {
                "char": "🌴",
                "name": "palm_tree"
            },
            {
                "char": "🌵",
                "name": "cactus"
            },
            {
                "char": "🌾",
                "name": "ear_of_rice"
            },
            {
                "char": "🌿",
                "name": "herb"
            },
            {
                "char": "☘️",
                "name": "shamrock"
            },
            {
                "char": "🍀",
                "name": "four_leaf_clover"
            },
            {
                "char": "🍁",
                "name": "maple_leaf"
            },
            {
                "char": "🍂",
                "name": "fallen_leaf"
            },
            {
                "char": "🍃",
                "name": "leaves"
            },
            {
                "char": "🪹",
                "name": "empty_nest"
            },
            {
                "char": "🪺",
                "name": "nest_with_eggs"
            },
            {
                "char": "🍄",
                "name": "mushroom"
            }
        ]
    },
    {
        "id": "food",
        "icon": "bi-apple",
        "title": "Food & Drink",
        "items": [
            {
                "char": "🍇",
                "name": "grapes"
            },
            {
                "char": "🍈",
                "name": "melon"
            },
            {
                "char": "🍉",
                "name": "watermelon"
            },
            {
                "char": "🍊",
                "name": "tangerine"
            },
            {
                "char": "🍋",
                "name": "lemon"
            },
            {
                "char": "🍌",
                "name": "banana"
            },
            {
                "char": "🍍",
                "name": "pineapple"
            },
            {
                "char": "🥭",
                "name": "mango"
            },
            {
                "char": "🍎",
                "name": "apple"
            },
            {
                "char": "🍏",
                "name": "green_apple"
            },
            {
                "char": "🍐",
                "name": "pear"
            },
            {
                "char": "🍑",
                "name": "peach"
            },
            {
                "char": "🍒",
                "name": "cherries"
            },
            {
                "char": "🍓",
                "name": "strawberry"
            },
            {
                "char": "🫐",
                "name": "blueberries"
            },
            {
                "char": "🥝",
                "name": "kiwi_fruit"
            },
            {
                "char": "🍅",
                "name": "tomato"
            },
            {
                "char": "🫒",
                "name": "olive"
            },
            {
                "char": "🥥",
                "name": "coconut"
            },
            {
                "char": "🥑",
                "name": "avocado"
            },
            {
                "char": "🍆",
                "name": "eggplant"
            },
            {
                "char": "🥔",
                "name": "potato"
            },
            {
                "char": "🥕",
                "name": "carrot"
            },
            {
                "char": "🌽",
                "name": "corn"
            },
            {
                "char": "🌶️",
                "name": "hot_pepper"
            },
            {
                "char": "🫑",
                "name": "bell_pepper"
            },
            {
                "char": "🥒",
                "name": "cucumber"
            },
            {
                "char": "🥬",
                "name": "leafy_green"
            },
            {
                "char": "🥦",
                "name": "broccoli"
            },
            {
                "char": "🧄",
                "name": "garlic"
            },
            {
                "char": "🧅",
                "name": "onion"
            },
            {
                "char": "🥜",
                "name": "peanuts"
            },
            {
                "char": "🫘",
                "name": "beans"
            },
            {
                "char": "🌰",
                "name": "chestnut"
            },

            {
                "char": "🍞",
                "name": "bread"
            },
            {
                "char": "🥐",
                "name": "croissant"
            },
            {
                "char": "🥖",
                "name": "baguette_bread"
            },
            {
                "char": "🫓",
                "name": "flatbread"
            },
            {
                "char": "🥨",
                "name": "pretzel"
            },
            {
                "char": "🥯",
                "name": "bagel"
            },
            {
                "char": "🥞",
                "name": "pancakes"
            },
            {
                "char": "🧇",
                "name": "waffle"
            },
            {
                "char": "🧀",
                "name": "cheese"
            },
            {
                "char": "🍖",
                "name": "meat_on_bone"
            },
            {
                "char": "🍗",
                "name": "poultry_leg"
            },
            {
                "char": "🥩",
                "name": "cut_of_meat"
            },
            {
                "char": "🥓",
                "name": "bacon"
            },
            {
                "char": "🍔",
                "name": "hamburger"
            },
            {
                "char": "🍟",
                "name": "fries"
            },
            {
                "char": "🍕",
                "name": "pizza"
            },
            {
                "char": "🌭",
                "name": "hotdog"
            },
            {
                "char": "🥪",
                "name": "sandwich"
            },
            {
                "char": "🌮",
                "name": "taco"
            },
            {
                "char": "🌯",
                "name": "burrito"
            },
            {
                "char": "🫔",
                "name": "tamale"
            },
            {
                "char": "🥙",
                "name": "stuffed_flatbread"
            },
            {
                "char": "🧆",
                "name": "falafel"
            },
            {
                "char": "🥚",
                "name": "egg"
            },
            {
                "char": "🍳",
                "name": "fried_egg"
            },
            {
                "char": "🥘",
                "name": "shallow_pan_of_food"
            },
            {
                "char": "🍲",
                "name": "stew"
            },
            {
                "char": "🫕",
                "name": "fondue"
            },
            {
                "char": "🥣",
                "name": "bowl_with_spoon"
            },
            {
                "char": "🥗",
                "name": "green_salad"
            },
            {
                "char": "🍿",
                "name": "popcorn"
            },
            {
                "char": "🧈",
                "name": "butter"
            },
            {
                "char": "🧂",
                "name": "salt"
            },
            {
                "char": "🥫",
                "name": "canned_food"
            },
            {
                "char": "🍱",
                "name": "bento"
            },
            {
                "char": "🍘",
                "name": "rice_cracker"
            },
            {
                "char": "🍙",
                "name": "rice_ball"
            },
            {
                "char": "🍚",
                "name": "rice"
            },
            {
                "char": "🍛",
                "name": "curry"
            },
            {
                "char": "🍜",
                "name": "ramen"
            },
            {
                "char": "🍝",
                "name": "spaghetti"
            },
            {
                "char": "🍠",
                "name": "sweet_potato"
            },
            {
                "char": "🍢",
                "name": "oden"
            },
            {
                "char": "🍣",
                "name": "sushi"
            },
            {
                "char": "🍤",
                "name": "fried_shrimp"
            },
            {
                "char": "🍥",
                "name": "fish_cake"
            },
            {
                "char": "🥮",
                "name": "moon_cake"
            },
            {
                "char": "🍡",
                "name": "dango"
            },
            {
                "char": "🥟",
                "name": "dumpling"
            },
            {
                "char": "🥠",
                "name": "fortune_cookie"
            },
            {
                "char": "🥡",
                "name": "takeout_box"
            },
            {
                "char": "🦀",
                "name": "crab"
            },
            {
                "char": "🦞",
                "name": "lobster"
            },
            {
                "char": "🦐",
                "name": "shrimp"
            },
            {
                "char": "🦑",
                "name": "squid"
            },
            {
                "char": "🦪",
                "name": "oyster"
            },
            {
                "char": "🍦",
                "name": "icecream"
            },
            {
                "char": "🍧",
                "name": "shaved_ice"
            },
            {
                "char": "🍨",
                "name": "ice_cream"
            },
            {
                "char": "🍩",
                "name": "doughnut"
            },
            {
                "char": "🍪",
                "name": "cookie"
            },
            {
                "char": "🎂",
                "name": "birthday"
            },
            {
                "char": "🍰",
                "name": "cake"
            },
            {
                "char": "🧁",
                "name": "cupcake"
            },
            {
                "char": "🥧",
                "name": "pie"
            },
            {
                "char": "🍫",
                "name": "chocolate_bar"
            },
            {
                "char": "🍬",
                "name": "candy"
            },
            {
                "char": "🍭",
                "name": "lollipop"
            },
            {
                "char": "🍮",
                "name": "custard"
            },
            {
                "char": "🍯",
                "name": "honey_pot"
            },
            {
                "char": "🍼",
                "name": "baby_bottle"
            },
            {
                "char": "🥛",
                "name": "milk_glass"
            },
            {
                "char": "☕",
                "name": "coffee"
            },
            {
                "char": "🫖",
                "name": "teapot"
            },
            {
                "char": "🍵",
                "name": "tea"
            },
            {
                "char": "🍶",
                "name": "sake"
            },
            {
                "char": "🍾",
                "name": "champagne"
            },
            {
                "char": "🍷",
                "name": "wine_glass"
            },
            {
                "char": "🍸",
                "name": "cocktail"
            },
            {
                "char": "🍹",
                "name": "tropical_drink"
            },
            {
                "char": "🍺",
                "name": "beer"
            },
            {
                "char": "🍻",
                "name": "beers"
            },
            {
                "char": "🥂",
                "name": "clinking_glasses"
            },
            {
                "char": "🥃",
                "name": "tumbler_glass"
            },
            {
                "char": "🫗",
                "name": "pouring_liquid"
            },
            {
                "char": "🥤",
                "name": "cup_with_straw"
            },
            {
                "char": "🧋",
                "name": "bubble_tea"
            },
            {
                "char": "🧃",
                "name": "beverage_box"
            },
            {
                "char": "🧉",
                "name": "mate"
            },
            {
                "char": "🧊",
                "name": "ice_cube"
            },
            {
                "char": "🥢",
                "name": "chopsticks"
            },
            {
                "char": "🍽️",
                "name": "plate_with_cutlery"
            },
            {
                "char": "🍴",
                "name": "fork_and_knife"
            },
            {
                "char": "🥄",
                "name": "spoon"
            },
            {
                "char": "🔪",
                "name": "hocho"
            },
            {
                "char": "🫙",
                "name": "jar"
            },
            {
                "char": "🏺",
                "name": "amphora"
            }
        ]
    },
    {
        "id": "travel",
        "icon": "bi-car-front",
        "title": "Travel & Places",
        "items": [
            {
                "char": "🌍",
                "name": "earth_africa"
            },
            {
                "char": "🌎",
                "name": "earth_americas"
            },
            {
                "char": "🌏",
                "name": "earth_asia"
            },
            {
                "char": "🌐",
                "name": "globe_with_meridians"
            },
            {
                "char": "🗺️",
                "name": "world_map"
            },
            {
                "char": "🗾",
                "name": "japan"
            },
            {
                "char": "🧭",
                "name": "compass"
            },
            {
                "char": "🏔️",
                "name": "mountain_snow"
            },
            {
                "char": "⛰️",
                "name": "mountain"
            },
            {
                "char": "🌋",
                "name": "volcano"
            },
            {
                "char": "🗻",
                "name": "mount_fuji"
            },
            {
                "char": "🏕️",
                "name": "camping"
            },
            {
                "char": "🏖️",
                "name": "beach_umbrella"
            },
            {
                "char": "🏜️",
                "name": "desert"
            },
            {
                "char": "🏝️",
                "name": "desert_island"
            },
            {
                "char": "🏞️",
                "name": "national_park"
            },
            {
                "char": "🏟️",
                "name": "stadium"
            },
            {
                "char": "🏛️",
                "name": "classical_building"
            },
            {
                "char": "🏗️",
                "name": "building_construction"
            },
            {
                "char": "🧱",
                "name": "bricks"
            },
            {
                "char": "🏘️",
                "name": "houses"
            },
            {
                "char": "🏚️",
                "name": "derelict_house"
            },
            {
                "char": "🏠",
                "name": "house"
            },
            {
                "char": "🏡",
                "name": "house_with_garden"
            },
            {
                "char": "🏢",
                "name": "office"
            },
            {
                "char": "🏣",
                "name": "post_office"
            },
            {
                "char": "🏤",
                "name": "european_post_office"
            },
            {
                "char": "🏥",
                "name": "hospital"
            },
            {
                "char": "🏦",
                "name": "bank"
            },
            {
                "char": "🏨",
                "name": "hotel"
            },
            {
                "char": "🏩",
                "name": "love_hotel"
            },
            {
                "char": "🏪",
                "name": "convenience_store"
            },
            {
                "char": "🏫",
                "name": "school"
            },
            {
                "char": "🏬",
                "name": "department_store"
            },
            {
                "char": "🏭",
                "name": "factory"
            },
            {
                "char": "🏯",
                "name": "japanese_castle"
            },
            {
                "char": "🏰",
                "name": "european_castle"
            },
            {
                "char": "💒",
                "name": "wedding"
            },
            {
                "char": "🗼",
                "name": "tokyo_tower"
            },
            {
                "char": "🗽",
                "name": "statue_of_liberty"
            },
            {
                "char": "⛪",
                "name": "church"
            },
            {
                "char": "🕌",
                "name": "mosque"
            },
            {
                "char": "🛕",
                "name": "hindu_temple"
            },
            {
                "char": "🕍",
                "name": "synagogue"
            },
            {
                "char": "⛩️",
                "name": "shinto_shrine"
            },
            {
                "char": "🕋",
                "name": "kaaba"
            },
            {
                "char": "⛲",
                "name": "fountain"
            },
            {
                "char": "⛺",
                "name": "tent"
            },
            {
                "char": "🌁",
                "name": "foggy"
            },
            {
                "char": "🌃",
                "name": "night_with_stars"
            },
            {
                "char": "🏙️",
                "name": "cityscape"
            },
            {
                "char": "🌄",
                "name": "sunrise_over_mountains"
            },
            {
                "char": "🌅",
                "name": "sunrise"
            },
            {
                "char": "🌆",
                "name": "city_sunset"
            },
            {
                "char": "🌇",
                "name": "city_sunrise"
            },
            {
                "char": "🌉",
                "name": "bridge_at_night"
            },
            {
                "char": "♨️",
                "name": "hotsprings"
            },
            {
                "char": "🎠",
                "name": "carousel_horse"
            },
            {
                "char": "🛝",
                "name": "playground_slide"
            },
            {
                "char": "🎡",
                "name": "ferris_wheel"
            },
            {
                "char": "🎢",
                "name": "roller_coaster"
            },
            {
                "char": "💈",
                "name": "barber"
            },
            {
                "char": "🎪",
                "name": "circus_tent"
            },
            {
                "char": "🚂",
                "name": "steam_locomotive"
            },
            {
                "char": "🚃",
                "name": "railway_car"
            },
            {
                "char": "🚄",
                "name": "bullettrain_side"
            },
            {
                "char": "🚅",
                "name": "bullettrain_front"
            },
            {
                "char": "🚆",
                "name": "train2"
            },
            {
                "char": "🚇",
                "name": "metro"
            },
            {
                "char": "🚈",
                "name": "light_rail"
            },
            {
                "char": "🚉",
                "name": "station"
            },
            {
                "char": "🚊",
                "name": "tram"
            },
            {
                "char": "🚝",
                "name": "monorail"
            },
            {
                "char": "🚞",
                "name": "mountain_railway"
            },
            {
                "char": "🚋",
                "name": "train"
            },
            {
                "char": "🚌",
                "name": "bus"
            },
            {
                "char": "🚍",
                "name": "oncoming_bus"
            },
            {
                "char": "🚎",
                "name": "trolleybus"
            },
            {
                "char": "🚐",
                "name": "minibus"
            },
            {
                "char": "🚑",
                "name": "ambulance"
            },
            {
                "char": "🚒",
                "name": "fire_engine"
            },
            {
                "char": "🚓",
                "name": "police_car"
            },
            {
                "char": "🚔",
                "name": "oncoming_police_car"
            },
            {
                "char": "🚕",
                "name": "taxi"
            },
            {
                "char": "🚖",
                "name": "oncoming_taxi"
            },
            {
                "char": "🚗",
                "name": "car"
            },
            {
                "char": "🚘",
                "name": "oncoming_automobile"
            },
            {
                "char": "🚙",
                "name": "blue_car"
            },
            {
                "char": "🚚",
                "name": "truck"
            },
            {
                "char": "🚛",
                "name": "articulated_lorry"
            },
            {
                "char": "🚜",
                "name": "tractor"
            },
            {
                "char": "🏎️",
                "name": "racing_car"
            },
            {
                "char": "🏍️",
                "name": "motorcycle"
            },
            {
                "char": "🛵",
                "name": "motor_scooter"
            },
            {
                "char": "🦽",
                "name": "manual_wheelchair"
            },
            {
                "char": "🦼",
                "name": "motorized_wheelchair"
            },
            {
                "char": "🛺",
                "name": "auto_rickshaw"
            },
            {
                "char": "🚲",
                "name": "bike"
            },
            {
                "char": "🛴",
                "name": "kick_scooter"
            },
            {
                "char": "🛹",
                "name": "skateboard"
            },
            {
                "char": "🛼",
                "name": "roller_skate"
            },
            {
                "char": "🚏",
                "name": "busstop"
            },
            {
                "char": "🛣️",
                "name": "motorway"
            },
            {
                "char": "🛤️",
                "name": "railway_track"
            },
            {
                "char": "🛢️",
                "name": "oil_drum"
            },
            {
                "char": "⛽",
                "name": "fuelpump"
            },
            {
                "char": "🛞",
                "name": "wheel"
            },
            {
                "char": "🚨",
                "name": "rotating_light"
            },
            {
                "char": "🚥",
                "name": "traffic_light"
            },
            {
                "char": "🚦",
                "name": "vertical_traffic_light"
            },
            {
                "char": "🛑",
                "name": "stop_sign"
            },
            {
                "char": "🚧",
                "name": "construction"
            },
            {
                "char": "⚓",
                "name": "anchor"
            },
            {
                "char": "🛟",
                "name": "ring_buoy"
            },
            {
                "char": "⛵",
                "name": "boat"
            },
            {
                "char": "🛶",
                "name": "canoe"
            },
            {
                "char": "🚤",
                "name": "speedboat"
            },
            {
                "char": "🛳️",
                "name": "passenger_ship"
            },
            {
                "char": "⛴️",
                "name": "ferry"
            },
            {
                "char": "🛥️",
                "name": "motor_boat"
            },
            {
                "char": "🚢",
                "name": "ship"
            },
            {
                "char": "✈️",
                "name": "airplane"
            },
            {
                "char": "🛩️",
                "name": "small_airplane"
            },
            {
                "char": "🛫",
                "name": "flight_departure"
            },
            {
                "char": "🛬",
                "name": "flight_arrival"
            },
            {
                "char": "🪂",
                "name": "parachute"
            },
            {
                "char": "💺",
                "name": "seat"
            },
            {
                "char": "🚁",
                "name": "helicopter"
            },
            {
                "char": "🚟",
                "name": "suspension_railway"
            },
            {
                "char": "🚠",
                "name": "mountain_cableway"
            },
            {
                "char": "🚡",
                "name": "aerial_tramway"
            },
            {
                "char": "🛰️",
                "name": "artificial_satellite"
            },
            {
                "char": "🚀",
                "name": "rocket"
            },
            {
                "char": "🛸",
                "name": "flying_saucer"
            },
            {
                "char": "🛎️",
                "name": "bellhop_bell"
            },
            {
                "char": "🧳",
                "name": "luggage"
            },
            {
                "char": "⌛",
                "name": "hourglass"
            },
            {
                "char": "⏳",
                "name": "hourglass_flowing_sand"
            },
            {
                "char": "⌚",
                "name": "watch"
            },
            {
                "char": "⏰",
                "name": "alarm_clock"
            },
            {
                "char": "⏱️",
                "name": "stopwatch"
            },
            {
                "char": "⏲️",
                "name": "timer_clock"
            },
            {
                "char": "🕰️",
                "name": "mantelpiece_clock"
            },
            {
                "char": "🕛",
                "name": "clock12"
            },
            {
                "char": "🕧",
                "name": "clock1230"
            },
            {
                "char": "🕐",
                "name": "clock1"
            },
            {
                "char": "🕜",
                "name": "clock130"
            },
            {
                "char": "🕑",
                "name": "clock2"
            },
            {
                "char": "🕝",
                "name": "clock230"
            },
            {
                "char": "🕒",
                "name": "clock3"
            },
            {
                "char": "🕞",
                "name": "clock330"
            },
            {
                "char": "🕓",
                "name": "clock4"
            },
            {
                "char": "🕟",
                "name": "clock430"
            },
            {
                "char": "🕔",
                "name": "clock5"
            },
            {
                "char": "🕠",
                "name": "clock530"
            },
            {
                "char": "🕕",
                "name": "clock6"
            },
            {
                "char": "🕡",
                "name": "clock630"
            },
            {
                "char": "🕖",
                "name": "clock7"
            },
            {
                "char": "🕢",
                "name": "clock730"
            },
            {
                "char": "🕗",
                "name": "clock8"
            },
            {
                "char": "🕣",
                "name": "clock830"
            },
            {
                "char": "🕘",
                "name": "clock9"
            },
            {
                "char": "🕤",
                "name": "clock930"
            },
            {
                "char": "🕙",
                "name": "clock10"
            },
            {
                "char": "🕥",
                "name": "clock1030"
            },
            {
                "char": "🕚",
                "name": "clock11"
            },
            {
                "char": "🕦",
                "name": "clock1130"
            },
            {
                "char": "🌑",
                "name": "new_moon"
            },
            {
                "char": "🌒",
                "name": "waxing_crescent_moon"
            },
            {
                "char": "🌓",
                "name": "first_quarter_moon"
            },
            {
                "char": "🌔",
                "name": "moon"
            },
            {
                "char": "🌕",
                "name": "full_moon"
            },
            {
                "char": "🌖",
                "name": "waning_gibbous_moon"
            },
            {
                "char": "🌗",
                "name": "last_quarter_moon"
            },
            {
                "char": "🌘",
                "name": "waning_crescent_moon"
            },
            {
                "char": "🌙",
                "name": "crescent_moon"
            },
            {
                "char": "🌚",
                "name": "new_moon_with_face"
            },
            {
                "char": "🌛",
                "name": "first_quarter_moon_with_face"
            },
            {
                "char": "🌜",
                "name": "last_quarter_moon_with_face"
            },
            {
                "char": "🌡️",
                "name": "thermometer"
            },
            {
                "char": "☀️",
                "name": "sunny"
            },
            {
                "char": "🌝",
                "name": "full_moon_with_face"
            },
            {
                "char": "🌞",
                "name": "sun_with_face"
            },
            {
                "char": "🪐",
                "name": "ringed_planet"
            },
            {
                "char": "⭐",
                "name": "star"
            },
            {
                "char": "🌟",
                "name": "star2"
            },
            {
                "char": "🌠",
                "name": "stars"
            },
            {
                "char": "🌌",
                "name": "milky_way"
            },
            {
                "char": "☁️",
                "name": "cloud"
            },
            {
                "char": "⛅",
                "name": "partly_sunny"
            },
            {
                "char": "⛈️",
                "name": "cloud_with_lightning_and_rain"
            },
            {
                "char": "🌤️",
                "name": "sun_behind_small_cloud"
            },
            {
                "char": "🌥️",
                "name": "sun_behind_large_cloud"
            },
            {
                "char": "🌦️",
                "name": "sun_behind_rain_cloud"
            },
            {
                "char": "🌧️",
                "name": "cloud_with_rain"
            },
            {
                "char": "🌨️",
                "name": "cloud_with_snow"
            },
            {
                "char": "🌩️",
                "name": "cloud_with_lightning"
            },
            {
                "char": "🌪️",
                "name": "tornado"
            },
            {
                "char": "🌫️",
                "name": "fog"
            },
            {
                "char": "🌬️",
                "name": "wind_face"
            },
            {
                "char": "🌀",
                "name": "cyclone"
            },
            {
                "char": "🌈",
                "name": "rainbow"
            },
            {
                "char": "🌂",
                "name": "closed_umbrella"
            },
            {
                "char": "☂️",
                "name": "open_umbrella"
            },
            {
                "char": "☔",
                "name": "umbrella"
            },
            {
                "char": "⛱️",
                "name": "parasol_on_ground"
            },
            {
                "char": "⚡",
                "name": "zap"
            },
            {
                "char": "❄️",
                "name": "snowflake"
            },
            {
                "char": "☃️",
                "name": "snowman_with_snow"
            },
            {
                "char": "⛄",
                "name": "snowman"
            },
            {
                "char": "☄️",
                "name": "comet"
            },
            {
                "char": "🔥",
                "name": "fire"
            },
            {
                "char": "💧",
                "name": "droplet"
            },
            {
                "char": "🌊",
                "name": "ocean"
            }
        ]
    },
    {
        "id": "activities",
        "icon": "bi-bicycle",
        "title": "Activities",
        "items": [
            {
                "char": "🎃",
                "name": "jack_o_lantern"
            },
            {
                "char": "🎄",
                "name": "christmas_tree"
            },
            {
                "char": "🎆",
                "name": "fireworks"
            },
            {
                "char": "🎇",
                "name": "sparkler"
            },
            {
                "char": "🧨",
                "name": "firecracker"
            },
            {
                "char": "✨",
                "name": "sparkles"
            },
            {
                "char": "🎈",
                "name": "balloon"
            },
            {
                "char": "🎉",
                "name": "tada"
            },
            {
                "char": "🎊",
                "name": "confetti_ball"
            },
            {
                "char": "🎋",
                "name": "tanabata_tree"
            },
            {
                "char": "🎍",
                "name": "bamboo"
            },
            {
                "char": "🎎",
                "name": "dolls"
            },
            {
                "char": "🎏",
                "name": "flags"
            },
            {
                "char": "🎐",
                "name": "wind_chime"
            },
            {
                "char": "🎑",
                "name": "rice_scene"
            },
            {
                "char": "🧧",
                "name": "red_envelope"
            },
            {
                "char": "🎀",
                "name": "ribbon"
            },
            {
                "char": "🎁",
                "name": "gift"
            },
            {
                "char": "🎗️",
                "name": "reminder_ribbon"
            },
            {
                "char": "🎟️",
                "name": "tickets"
            },
            {
                "char": "🎫",
                "name": "ticket"
            },
            {
                "char": "🎖️",
                "name": "medal_military"
            },
            {
                "char": "🏆",
                "name": "trophy"
            },
            {
                "char": "🏅",
                "name": "medal_sports"
            },
            {
                "char": "🥇",
                "name": "1st_place_medal"
            },
            {
                "char": "🥈",
                "name": "2nd_place_medal"
            },
            {
                "char": "🥉",
                "name": "3rd_place_medal"
            },
            {
                "char": "⚽",
                "name": "soccer"
            },
            {
                "char": "⚾",
                "name": "baseball"
            },
            {
                "char": "🥎",
                "name": "softball"
            },
            {
                "char": "🏀",
                "name": "basketball"
            },
            {
                "char": "🏐",
                "name": "volleyball"
            },
            {
                "char": "🏈",
                "name": "football"
            },
            {
                "char": "🏉",
                "name": "rugby_football"
            },
            {
                "char": "🎾",
                "name": "tennis"
            },
            {
                "char": "🥏",
                "name": "flying_disc"
            },
            {
                "char": "🎳",
                "name": "bowling"
            },
            {
                "char": "🏏",
                "name": "cricket_game"
            },
            {
                "char": "🏑",
                "name": "field_hockey"
            },
            {
                "char": "🏒",
                "name": "ice_hockey"
            },
            {
                "char": "🥍",
                "name": "lacrosse"
            },
            {
                "char": "🏓",
                "name": "ping_pong"
            },
            {
                "char": "🏸",
                "name": "badminton"
            },
            {
                "char": "🥊",
                "name": "boxing_glove"
            },
            {
                "char": "🥋",
                "name": "martial_arts_uniform"
            },
            {
                "char": "🥅",
                "name": "goal_net"
            },
            {
                "char": "⛳",
                "name": "golf"
            },
            {
                "char": "⛸️",
                "name": "ice_skate"
            },
            {
                "char": "🎣",
                "name": "fishing_pole_and_fish"
            },
            {
                "char": "🤿",
                "name": "diving_mask"
            },
            {
                "char": "🎽",
                "name": "running_shirt_with_sash"
            },
            {
                "char": "🎿",
                "name": "ski"
            },
            {
                "char": "🛷",
                "name": "sled"
            },
            {
                "char": "🥌",
                "name": "curling_stone"
            },
            {
                "char": "🎯",
                "name": "dart"
            },
            {
                "char": "🪀",
                "name": "yo_yo"
            },
            {
                "char": "🪁",
                "name": "kite"
            },
            {
                "char": "🔫",
                "name": "gun"
            },
            {
                "char": "🎱",
                "name": "8ball"
            },
            {
                "char": "🔮",
                "name": "crystal_ball"
            },
            {
                "char": "🪄",
                "name": "magic_wand"
            },
            {
                "char": "🎮",
                "name": "video_game"
            },
            {
                "char": "🕹️",
                "name": "joystick"
            },
            {
                "char": "🎰",
                "name": "slot_machine"
            },
            {
                "char": "🎲",
                "name": "game_die"
            },
            {
                "char": "🧩",
                "name": "jigsaw"
            },
            {
                "char": "🧸",
                "name": "teddy_bear"
            },
            {
                "char": "🪅",
                "name": "pinata"
            },
            {
                "char": "🪩",
                "name": "mirror_ball"
            },
            {
                "char": "🪆",
                "name": "nesting_dolls"
            },
            {
                "char": "♠️",
                "name": "spades"
            },
            {
                "char": "♥️",
                "name": "hearts"
            },
            {
                "char": "♦️",
                "name": "diamonds"
            },
            {
                "char": "♣️",
                "name": "clubs"
            },
            {
                "char": "♟️",
                "name": "chess_pawn"
            },
            {
                "char": "🃏",
                "name": "black_joker"
            },
            {
                "char": "🀄",
                "name": "mahjong"
            },
            {
                "char": "🎴",
                "name": "flower_playing_cards"
            },
            {
                "char": "🎭",
                "name": "performing_arts"
            },
            {
                "char": "🖼️",
                "name": "framed_picture"
            },
            {
                "char": "🎨",
                "name": "art"
            },
            {
                "char": "🧵",
                "name": "thread"
            },
            {
                "char": "🪡",
                "name": "sewing_needle"
            },
            {
                "char": "🧶",
                "name": "yarn"
            },
            {
                "char": "🪢",
                "name": "knot"
            }
        ]
    },
    {
        "id": "objects",
        "icon": "bi-lightbulb",
        "title": "Objects",
        "items": [
            {
                "char": "👓",
                "name": "eyeglasses"
            },
            {
                "char": "🕶️",
                "name": "dark_sunglasses"
            },
            {
                "char": "🥽",
                "name": "goggles"
            },
            {
                "char": "🥼",
                "name": "lab_coat"
            },
            {
                "char": "🦺",
                "name": "safety_vest"
            },
            {
                "char": "👔",
                "name": "necktie"
            },
            {
                "char": "👕",
                "name": "shirt"
            },
            {
                "char": "👖",
                "name": "jeans"
            },
            {
                "char": "🧣",
                "name": "scarf"
            },
            {
                "char": "🧤",
                "name": "gloves"
            },
            {
                "char": "🧥",
                "name": "coat"
            },
            {
                "char": "🧦",
                "name": "socks"
            },
            {
                "char": "👗",
                "name": "dress"
            },
            {
                "char": "👘",
                "name": "kimono"
            },
            {
                "char": "🥻",
                "name": "sari"
            },
            {
                "char": "🩱",
                "name": "one_piece_swimsuit"
            },
            {
                "char": "🩲",
                "name": "swim_brief"
            },
            {
                "char": "🩳",
                "name": "shorts"
            },
            {
                "char": "👙",
                "name": "bikini"
            },
            {
                "char": "👚",
                "name": "womans_clothes"
            },
            {
                "char": "🪭",
                "name": "folding_hand_fan"
            },
            {
                "char": "👛",
                "name": "purse"
            },
            {
                "char": "👜",
                "name": "handbag"
            },
            {
                "char": "👝",
                "name": "pouch"
            },
            {
                "char": "🛍️",
                "name": "shopping"
            },
            {
                "char": "🎒",
                "name": "school_satchel"
            },
            {
                "char": "🩴",
                "name": "thong_sandal"
            },
            {
                "char": "👞",
                "name": "mans_shoe"
            },
            {
                "char": "👟",
                "name": "athletic_shoe"
            },
            {
                "char": "🥾",
                "name": "hiking_boot"
            },
            {
                "char": "🥿",
                "name": "flat_shoe"
            },
            {
                "char": "👠",
                "name": "high_heel"
            },
            {
                "char": "👡",
                "name": "sandal"
            },
            {
                "char": "🩰",
                "name": "ballet_shoes"
            },
            {
                "char": "👢",
                "name": "boot"
            },
            {
                "char": "🪮",
                "name": "hair_pick"
            },
            {
                "char": "👑",
                "name": "crown"
            },
            {
                "char": "👒",
                "name": "womans_hat"
            },
            {
                "char": "🎩",
                "name": "tophat"
            },
            {
                "char": "🎓",
                "name": "mortar_board"
            },
            {
                "char": "🧢",
                "name": "billed_cap"
            },
            {
                "char": "🪖",
                "name": "military_helmet"
            },
            {
                "char": "⛑️",
                "name": "rescue_worker_helmet"
            },
            {
                "char": "📿",
                "name": "prayer_beads"
            },
            {
                "char": "💄",
                "name": "lipstick"
            },
            {
                "char": "💍",
                "name": "ring"
            },
            {
                "char": "💎",
                "name": "gem"
            },
            {
                "char": "🔇",
                "name": "mute"
            },
            {
                "char": "🔈",
                "name": "speaker"
            },
            {
                "char": "🔉",
                "name": "sound"
            },
            {
                "char": "🔊",
                "name": "loud_sound"
            },
            {
                "char": "📢",
                "name": "loudspeaker"
            },
            {
                "char": "📣",
                "name": "mega"
            },
            {
                "char": "📯",
                "name": "postal_horn"
            },
            {
                "char": "🔔",
                "name": "bell"
            },
            {
                "char": "🔕",
                "name": "no_bell"
            },
            {
                "char": "🎼",
                "name": "musical_score"
            },
            {
                "char": "🎵",
                "name": "musical_note"
            },
            {
                "char": "🎶",
                "name": "notes"
            },
            {
                "char": "🎙️",
                "name": "studio_microphone"
            },
            {
                "char": "🎚️",
                "name": "level_slider"
            },
            {
                "char": "🎛️",
                "name": "control_knobs"
            },
            {
                "char": "🎤",
                "name": "microphone"
            },
            {
                "char": "🎧",
                "name": "headphones"
            },
            {
                "char": "📻",
                "name": "radio"
            },
            {
                "char": "🎷",
                "name": "saxophone"
            },
            {
                "char": "🪗",
                "name": "accordion"
            },
            {
                "char": "🎸",
                "name": "guitar"
            },
            {
                "char": "🎹",
                "name": "musical_keyboard"
            },
            {
                "char": "🎺",
                "name": "trumpet"
            },
            {
                "char": "🎻",
                "name": "violin"
            },
            {
                "char": "🪕",
                "name": "banjo"
            },
            {
                "char": "🥁",
                "name": "drum"
            },
            {
                "char": "🪘",
                "name": "long_drum"
            },
            {
                "char": "🪇",
                "name": "maracas"
            },
            {
                "char": "🪈",
                "name": "flute"
            },
            {
                "char": "📱",
                "name": "iphone"
            },
            {
                "char": "📲",
                "name": "calling"
            },
            {
                "char": "☎️",
                "name": "phone"
            },
            {
                "char": "📞",
                "name": "telephone_receiver"
            },
            {
                "char": "📟",
                "name": "pager"
            },
            {
                "char": "📠",
                "name": "fax"
            },
            {
                "char": "🔋",
                "name": "battery"
            },
            {
                "char": "🪫",
                "name": "low_battery"
            },
            {
                "char": "🔌",
                "name": "electric_plug"
            },
            {
                "char": "💻",
                "name": "computer"
            },
            {
                "char": "🖥️",
                "name": "desktop_computer"
            },
            {
                "char": "🖨️",
                "name": "printer"
            },
            {
                "char": "⌨️",
                "name": "keyboard"
            },
            {
                "char": "🖱️",
                "name": "computer_mouse"
            },
            {
                "char": "🖲️",
                "name": "trackball"
            },
            {
                "char": "💽",
                "name": "minidisc"
            },
            {
                "char": "💾",
                "name": "floppy_disk"
            },
            {
                "char": "💿",
                "name": "cd"
            },
            {
                "char": "📀",
                "name": "dvd"
            },
            {
                "char": "🧮",
                "name": "abacus"
            },
            {
                "char": "🎥",
                "name": "movie_camera"
            },
            {
                "char": "🎞️",
                "name": "film_strip"
            },
            {
                "char": "📽️",
                "name": "film_projector"
            },
            {
                "char": "🎬",
                "name": "clapper"
            },
            {
                "char": "📺",
                "name": "tv"
            },
            {
                "char": "📷",
                "name": "camera"
            },
            {
                "char": "📸",
                "name": "camera_flash"
            },
            {
                "char": "📹",
                "name": "video_camera"
            },
            {
                "char": "📼",
                "name": "vhs"
            },
            {
                "char": "🔍",
                "name": "mag"
            },
            {
                "char": "🔎",
                "name": "mag_right"
            },
            {
                "char": "🕯️",
                "name": "candle"
            },
            {
                "char": "💡",
                "name": "bulb"
            },
            {
                "char": "🔦",
                "name": "flashlight"
            },
            {
                "char": "🏮",
                "name": "izakaya_lantern"
            },
            {
                "char": "🪔",
                "name": "diya_lamp"
            },
            {
                "char": "📔",
                "name": "notebook_with_decorative_cover"
            },
            {
                "char": "📕",
                "name": "closed_book"
            },
            {
                "char": "📖",
                "name": "book"
            },
            {
                "char": "📗",
                "name": "green_book"
            },
            {
                "char": "📘",
                "name": "blue_book"
            },
            {
                "char": "📙",
                "name": "orange_book"
            },
            {
                "char": "📚",
                "name": "books"
            },
            {
                "char": "📓",
                "name": "notebook"
            },
            {
                "char": "📒",
                "name": "ledger"
            },
            {
                "char": "📃",
                "name": "page_with_curl"
            },
            {
                "char": "📜",
                "name": "scroll"
            },
            {
                "char": "📄",
                "name": "page_facing_up"
            },
            {
                "char": "📰",
                "name": "newspaper"
            },
            {
                "char": "🗞️",
                "name": "newspaper_roll"
            },
            {
                "char": "📑",
                "name": "bookmark_tabs"
            },
            {
                "char": "🔖",
                "name": "bookmark"
            },
            {
                "char": "🏷️",
                "name": "label"
            },
            {
                "char": "💰",
                "name": "moneybag"
            },
            {
                "char": "🪙",
                "name": "coin"
            },
            {
                "char": "💴",
                "name": "yen"
            },
            {
                "char": "💵",
                "name": "dollar"
            },
            {
                "char": "💶",
                "name": "euro"
            },
            {
                "char": "💷",
                "name": "pound"
            },
            {
                "char": "💸",
                "name": "money_with_wings"
            },
            {
                "char": "💳",
                "name": "credit_card"
            },
            {
                "char": "🧾",
                "name": "receipt"
            },
            {
                "char": "💹",
                "name": "chart"
            },
            {
                "char": "✉️",
                "name": "envelope"
            },
            {
                "char": "📧",
                "name": "email"
            },
            {
                "char": "📨",
                "name": "incoming_envelope"
            },
            {
                "char": "📩",
                "name": "envelope_with_arrow"
            },
            {
                "char": "📤",
                "name": "outbox_tray"
            },
            {
                "char": "📥",
                "name": "inbox_tray"
            },
            {
                "char": "📦",
                "name": "package"
            },
            {
                "char": "📫",
                "name": "mailbox"
            },
            {
                "char": "📪",
                "name": "mailbox_closed"
            },
            {
                "char": "📬",
                "name": "mailbox_with_mail"
            },
            {
                "char": "📭",
                "name": "mailbox_with_no_mail"
            },
            {
                "char": "📮",
                "name": "postbox"
            },
            {
                "char": "🗳️",
                "name": "ballot_box"
            },
            {
                "char": "✏️",
                "name": "pencil2"
            },
            {
                "char": "✒️",
                "name": "black_nib"
            },
            {
                "char": "🖋️",
                "name": "fountain_pen"
            },
            {
                "char": "🖊️",
                "name": "pen"
            },
            {
                "char": "🖌️",
                "name": "paintbrush"
            },
            {
                "char": "🖍️",
                "name": "crayon"
            },
            {
                "char": "📝",
                "name": "memo"
            },
            {
                "char": "💼",
                "name": "briefcase"
            },
            {
                "char": "📁",
                "name": "file_folder"
            },
            {
                "char": "📂",
                "name": "open_file_folder"
            },
            {
                "char": "🗂️",
                "name": "card_index_dividers"
            },
            {
                "char": "📅",
                "name": "date"
            },
            {
                "char": "📆",
                "name": "calendar"
            },
            {
                "char": "🗒️",
                "name": "spiral_notepad"
            },
            {
                "char": "🗓️",
                "name": "spiral_calendar"
            },
            {
                "char": "📇",
                "name": "card_index"
            },
            {
                "char": "📈",
                "name": "chart_with_upwards_trend"
            },
            {
                "char": "📉",
                "name": "chart_with_downwards_trend"
            },
            {
                "char": "📊",
                "name": "bar_chart"
            },
            {
                "char": "📋",
                "name": "clipboard"
            },
            {
                "char": "📌",
                "name": "pushpin"
            },
            {
                "char": "📍",
                "name": "round_pushpin"
            },
            {
                "char": "📎",
                "name": "paperclip"
            },
            {
                "char": "🖇️",
                "name": "paperclips"
            },
            {
                "char": "📏",
                "name": "straight_ruler"
            },
            {
                "char": "📐",
                "name": "triangular_ruler"
            },
            {
                "char": "✂️",
                "name": "scissors"
            },
            {
                "char": "🗃️",
                "name": "card_file_box"
            },
            {
                "char": "🗄️",
                "name": "file_cabinet"
            },
            {
                "char": "🗑️",
                "name": "wastebasket"
            },
            {
                "char": "🔒",
                "name": "lock"
            },
            {
                "char": "🔓",
                "name": "unlock"
            },
            {
                "char": "🔏",
                "name": "lock_with_ink_pen"
            },
            {
                "char": "🔐",
                "name": "closed_lock_with_key"
            },
            {
                "char": "🔑",
                "name": "key"
            },
            {
                "char": "🗝️",
                "name": "old_key"
            },
            {
                "char": "🔨",
                "name": "hammer"
            },
            {
                "char": "🪓",
                "name": "axe"
            },
            {
                "char": "⛏️",
                "name": "pick"
            },
            {
                "char": "⚒️",
                "name": "hammer_and_pick"
            },
            {
                "char": "🛠️",
                "name": "hammer_and_wrench"
            },
            {
                "char": "🗡️",
                "name": "dagger"
            },
            {
                "char": "⚔️",
                "name": "crossed_swords"
            },
            {
                "char": "💣",
                "name": "bomb"
            },
            {
                "char": "🪃",
                "name": "boomerang"
            },
            {
                "char": "🏹",
                "name": "bow_and_arrow"
            },
            {
                "char": "🛡️",
                "name": "shield"
            },
            {
                "char": "🪚",
                "name": "carpentry_saw"
            },
            {
                "char": "🔧",
                "name": "wrench"
            },
            {
                "char": "🪛",
                "name": "screwdriver"
            },
            {
                "char": "🔩",
                "name": "nut_and_bolt"
            },
            {
                "char": "⚙️",
                "name": "gear"
            },
            {
                "char": "🗜️",
                "name": "clamp"
            },
            {
                "char": "⚖️",
                "name": "balance_scale"
            },
            {
                "char": "🦯",
                "name": "probing_cane"
            },
            {
                "char": "🔗",
                "name": "link"
            },
            {
                "char": "⛓️",
                "name": "chains"
            },
            {
                "char": "🪝",
                "name": "hook"
            },
            {
                "char": "🧰",
                "name": "toolbox"
            },
            {
                "char": "🧲",
                "name": "magnet"
            },
            {
                "char": "🪜",
                "name": "ladder"
            },
            {
                "char": "⚗️",
                "name": "alembic"
            },
            {
                "char": "🧪",
                "name": "test_tube"
            },
            {
                "char": "🧫",
                "name": "petri_dish"
            },
            {
                "char": "🧬",
                "name": "dna"
            },
            {
                "char": "🔬",
                "name": "microscope"
            },
            {
                "char": "🔭",
                "name": "telescope"
            },
            {
                "char": "📡",
                "name": "satellite"
            },
            {
                "char": "💉",
                "name": "syringe"
            },
            {
                "char": "🩸",
                "name": "drop_of_blood"
            },
            {
                "char": "💊",
                "name": "pill"
            },
            {
                "char": "🩹",
                "name": "adhesive_bandage"
            },
            {
                "char": "🩼",
                "name": "crutch"
            },
            {
                "char": "🩺",
                "name": "stethoscope"
            },
            {
                "char": "🩻",
                "name": "x_ray"
            },
            {
                "char": "🚪",
                "name": "door"
            },
            {
                "char": "🛗",
                "name": "elevator"
            },
            {
                "char": "🪞",
                "name": "mirror"
            },
            {
                "char": "🪟",
                "name": "window"
            },
            {
                "char": "🛏️",
                "name": "bed"
            },
            {
                "char": "🛋️",
                "name": "couch_and_lamp"
            },
            {
                "char": "🪑",
                "name": "chair"
            },
            {
                "char": "🚽",
                "name": "toilet"
            },
            {
                "char": "🪠",
                "name": "plunger"
            },
            {
                "char": "🚿",
                "name": "shower"
            },
            {
                "char": "🛁",
                "name": "bathtub"
            },
            {
                "char": "🪤",
                "name": "mouse_trap"
            },
            {
                "char": "🪒",
                "name": "razor"
            },
            {
                "char": "🧴",
                "name": "lotion_bottle"
            },
            {
                "char": "🧷",
                "name": "safety_pin"
            },
            {
                "char": "🧹",
                "name": "broom"
            },
            {
                "char": "🧺",
                "name": "basket"
            },
            {
                "char": "🧻",
                "name": "roll_of_paper"
            },
            {
                "char": "🪣",
                "name": "bucket"
            },
            {
                "char": "🧼",
                "name": "soap"
            },
            {
                "char": "🫧",
                "name": "bubbles"
            },
            {
                "char": "🪥",
                "name": "toothbrush"
            },
            {
                "char": "🧽",
                "name": "sponge"
            },
            {
                "char": "🧯",
                "name": "fire_extinguisher"
            },
            {
                "char": "🛒",
                "name": "shopping_cart"
            },
            {
                "char": "🚬",
                "name": "smoking"
            },
            {
                "char": "⚰️",
                "name": "coffin"
            },
            {
                "char": "🪦",
                "name": "headstone"
            },
            {
                "char": "⚱️",
                "name": "funeral_urn"
            },
            {
                "char": "🧿",
                "name": "nazar_amulet"
            },
            {
                "char": "🪬",
                "name": "hamsa"
            },
            {
                "char": "🗿",
                "name": "moyai"
            },
            {
                "char": "🪧",
                "name": "placard"
            },
            {
                "char": "🪪",
                "name": "identification_card"
            }
        ]
    },
    {
        "id": "symbols",
        "icon": "bi-hash",
        "title": "Symbols",
        "items": [
            {
                "char": "🏧",
                "name": "atm"
            },
            {
                "char": "🚮",
                "name": "put_litter_in_its_place"
            },
            {
                "char": "🚰",
                "name": "potable_water"
            },
            {
                "char": "♿",
                "name": "wheelchair"
            },
            {
                "char": "🚹",
                "name": "mens"
            },
            {
                "char": "🚺",
                "name": "womens"
            },
            {
                "char": "🚻",
                "name": "restroom"
            },
            {
                "char": "🚼",
                "name": "baby_symbol"
            },
            {
                "char": "🚾",
                "name": "wc"
            },
            {
                "char": "🛂",
                "name": "passport_control"
            },
            {
                "char": "🛃",
                "name": "customs"
            },
            {
                "char": "🛄",
                "name": "baggage_claim"
            },
            {
                "char": "🛅",
                "name": "left_luggage"
            },
            {
                "char": "⚠️",
                "name": "warning"
            },
            {
                "char": "🚸",
                "name": "children_crossing"
            },
            {
                "char": "⛔",
                "name": "no_entry"
            },
            {
                "char": "🚫",
                "name": "no_entry_sign"
            },
            {
                "char": "🚳",
                "name": "no_bicycles"
            },
            {
                "char": "🚭",
                "name": "no_smoking"
            },
            {
                "char": "🚯",
                "name": "do_not_litter"
            },
            {
                "char": "🚱",
                "name": "non-potable_water"
            },
            {
                "char": "🚷",
                "name": "no_pedestrians"
            },
            {
                "char": "📵",
                "name": "no_mobile_phones"
            },
            {
                "char": "🔞",
                "name": "underage"
            },
            {
                "char": "☢️",
                "name": "radioactive"
            },
            {
                "char": "☣️",
                "name": "biohazard"
            },
            {
                "char": "⬆️",
                "name": "arrow_up"
            },
            {
                "char": "↗️",
                "name": "arrow_upper_right"
            },
            {
                "char": "➡️",
                "name": "arrow_right"
            },
            {
                "char": "↘️",
                "name": "arrow_lower_right"
            },
            {
                "char": "⬇️",
                "name": "arrow_down"
            },
            {
                "char": "↙️",
                "name": "arrow_lower_left"
            },
            {
                "char": "⬅️",
                "name": "arrow_left"
            },
            {
                "char": "↖️",
                "name": "arrow_upper_left"
            },
            {
                "char": "↕️",
                "name": "arrow_up_down"
            },
            {
                "char": "↔️",
                "name": "left_right_arrow"
            },
            {
                "char": "↩️",
                "name": "leftwards_arrow_with_hook"
            },
            {
                "char": "↪️",
                "name": "arrow_right_hook"
            },
            {
                "char": "⤴️",
                "name": "arrow_heading_up"
            },
            {
                "char": "⤵️",
                "name": "arrow_heading_down"
            },
            {
                "char": "🔃",
                "name": "arrows_clockwise"
            },
            {
                "char": "🔄",
                "name": "arrows_counterclockwise"
            },
            {
                "char": "🔙",
                "name": "back"
            },
            {
                "char": "🔚",
                "name": "end"
            },
            {
                "char": "🔛",
                "name": "on"
            },
            {
                "char": "🔜",
                "name": "soon"
            },
            {
                "char": "🔝",
                "name": "top"
            },
            {
                "char": "🛐",
                "name": "place_of_worship"
            },
            {
                "char": "⚛️",
                "name": "atom_symbol"
            },
            {
                "char": "🕉️",
                "name": "om"
            },
            {
                "char": "✡️",
                "name": "star_of_david"
            },
            {
                "char": "☸️",
                "name": "wheel_of_dharma"
            },
            {
                "char": "☯️",
                "name": "yin_yang"
            },
            {
                "char": "✝️",
                "name": "latin_cross"
            },
            {
                "char": "☦️",
                "name": "orthodox_cross"
            },
            {
                "char": "☪️",
                "name": "star_and_crescent"
            },
            {
                "char": "☮️",
                "name": "peace_symbol"
            },
            {
                "char": "🕎",
                "name": "menorah"
            },
            {
                "char": "🔯",
                "name": "six_pointed_star"
            },
            {
                "char": "🪯",
                "name": "khanda"
            },
            {
                "char": "♈",
                "name": "aries"
            },
            {
                "char": "♉",
                "name": "taurus"
            },
            {
                "char": "♊",
                "name": "gemini"
            },
            {
                "char": "♋",
                "name": "cancer"
            },
            {
                "char": "♌",
                "name": "leo"
            },
            {
                "char": "♍",
                "name": "virgo"
            },
            {
                "char": "♎",
                "name": "libra"
            },
            {
                "char": "♏",
                "name": "scorpius"
            },
            {
                "char": "♐",
                "name": "sagittarius"
            },
            {
                "char": "♑",
                "name": "capricorn"
            },
            {
                "char": "♒",
                "name": "aquarius"
            },
            {
                "char": "♓",
                "name": "pisces"
            },
            {
                "char": "⛎",
                "name": "ophiuchus"
            },
            {
                "char": "🔀",
                "name": "twisted_rightwards_arrows"
            },
            {
                "char": "🔁",
                "name": "repeat"
            },
            {
                "char": "🔂",
                "name": "repeat_one"
            },
            {
                "char": "▶️",
                "name": "arrow_forward"
            },
            {
                "char": "⏩",
                "name": "fast_forward"
            },
            {
                "char": "⏭️",
                "name": "next_track_button"
            },
            {
                "char": "⏯️",
                "name": "play_or_pause_button"
            },
            {
                "char": "◀️",
                "name": "arrow_backward"
            },
            {
                "char": "⏪",
                "name": "rewind"
            },
            {
                "char": "⏮️",
                "name": "previous_track_button"
            },
            {
                "char": "🔼",
                "name": "arrow_up_small"
            },
            {
                "char": "⏫",
                "name": "arrow_double_up"
            },
            {
                "char": "🔽",
                "name": "arrow_down_small"
            },
            {
                "char": "⏬",
                "name": "arrow_double_down"
            },
            {
                "char": "⏸️",
                "name": "pause_button"
            },
            {
                "char": "⏹️",
                "name": "stop_button"
            },
            {
                "char": "⏺️",
                "name": "record_button"
            },
            {
                "char": "⏏️",
                "name": "eject_button"
            },
            {
                "char": "🎦",
                "name": "cinema"
            },
            {
                "char": "🔅",
                "name": "low_brightness"
            },
            {
                "char": "🔆",
                "name": "high_brightness"
            },
            {
                "char": "📶",
                "name": "signal_strength"
            },
            {
                "char": "🛜",
                "name": "wireless"
            },
            {
                "char": "📳",
                "name": "vibration_mode"
            },
            {
                "char": "📴",
                "name": "mobile_phone_off"
            },
            {
                "char": "♀️",
                "name": "female_sign"
            },
            {
                "char": "♂️",
                "name": "male_sign"
            },
            {
                "char": "⚧️",
                "name": "transgender_symbol"
            },
            {
                "char": "✖️",
                "name": "heavy_multiplication_x"
            },
            {
                "char": "➕",
                "name": "heavy_plus_sign"
            },
            {
                "char": "➖",
                "name": "heavy_minus_sign"
            },
            {
                "char": "➗",
                "name": "heavy_division_sign"
            },

            {
                "char": "♾️",
                "name": "infinity"
            },
            {
                "char": "‼️",
                "name": "bangbang"
            },
            {
                "char": "⁉️",
                "name": "interrobang"
            },
            {
                "char": "❓",
                "name": "question"
            },
            {
                "char": "❔",
                "name": "grey_question"
            },
            {
                "char": "❕",
                "name": "grey_exclamation"
            },
            {
                "char": "❗",
                "name": "exclamation"
            },
            {
                "char": "〰️",
                "name": "wavy_dash"
            },
            {
                "char": "💱",
                "name": "currency_exchange"
            },
            {
                "char": "💲",
                "name": "heavy_dollar_sign"
            },
            {
                "char": "⚕️",
                "name": "medical_symbol"
            },
            {
                "char": "♻️",
                "name": "recycle"
            },
            {
                "char": "⚜️",
                "name": "fleur_de_lis"
            },
            {
                "char": "🔱",
                "name": "trident"
            },
            {
                "char": "📛",
                "name": "name_badge"
            },
            {
                "char": "🔰",
                "name": "beginner"
            },
            {
                "char": "⭕",
                "name": "o"
            },
            {
                "char": "✅",
                "name": "white_check_mark"
            },
            {
                "char": "☑️",
                "name": "ballot_box_with_check"
            },
            {
                "char": "✔️",
                "name": "heavy_check_mark"
            },
            {
                "char": "❌",
                "name": "x"
            },
            {
                "char": "❎",
                "name": "negative_squared_cross_mark"
            },
            {
                "char": "➰",
                "name": "curly_loop"
            },
            {
                "char": "➿",
                "name": "loop"
            },
            {
                "char": "〽️",
                "name": "part_alternation_mark"
            },
            {
                "char": "✳️",
                "name": "eight_spoked_asterisk"
            },
            {
                "char": "✴️",
                "name": "eight_pointed_black_star"
            },
            {
                "char": "❇️",
                "name": "sparkle"
            },
            {
                "char": "©️",
                "name": "copyright"
            },
            {
                "char": "®️",
                "name": "registered"
            },
            {
                "char": "™️",
                "name": "tm"
            },
            {
                "char": "#️⃣",
                "name": "hash"
            },
            {
                "char": "*️⃣",
                "name": "asterisk"
            },
            {
                "char": "0️⃣",
                "name": "zero"
            },
            {
                "char": "1️⃣",
                "name": "one"
            },
            {
                "char": "2️⃣",
                "name": "two"
            },
            {
                "char": "3️⃣",
                "name": "three"
            },
            {
                "char": "4️⃣",
                "name": "four"
            },
            {
                "char": "5️⃣",
                "name": "five"
            },
            {
                "char": "6️⃣",
                "name": "six"
            },
            {
                "char": "7️⃣",
                "name": "seven"
            },
            {
                "char": "8️⃣",
                "name": "eight"
            },
            {
                "char": "9️⃣",
                "name": "nine"
            },
            {
                "char": "🔟",
                "name": "keycap_ten"
            },
            {
                "char": "🔠",
                "name": "capital_abcd"
            },
            {
                "char": "🔡",
                "name": "abcd"
            },
            {
                "char": "🔢",
                "name": "1234"
            },
            {
                "char": "🔣",
                "name": "symbols"
            },
            {
                "char": "🔤",
                "name": "abc"
            },
            {
                "char": "🅰️",
                "name": "a"
            },
            {
                "char": "🆎",
                "name": "ab"
            },
            {
                "char": "🅱️",
                "name": "b"
            },
            {
                "char": "🆑",
                "name": "cl"
            },
            {
                "char": "🆒",
                "name": "cool"
            },
            {
                "char": "🆓",
                "name": "free"
            },
            {
                "char": "ℹ️",
                "name": "information_source"
            },
            {
                "char": "🆔",
                "name": "id"
            },
            {
                "char": "Ⓜ️",
                "name": "m"
            },
            {
                "char": "🆕",
                "name": "new"
            },
            {
                "char": "🆖",
                "name": "ng"
            },
            {
                "char": "🅾️",
                "name": "o2"
            },
            {
                "char": "🆗",
                "name": "ok"
            },
            {
                "char": "🅿️",
                "name": "parking"
            },
            {
                "char": "🆘",
                "name": "sos"
            },
            {
                "char": "🆙",
                "name": "up"
            },
            {
                "char": "🆚",
                "name": "vs"
            },
            {
                "char": "🈁",
                "name": "koko"
            },
            {
                "char": "🈂️",
                "name": "sa"
            },
            {
                "char": "🈷️",
                "name": "u6708"
            },
            {
                "char": "🈶",
                "name": "u6709"
            },
            {
                "char": "🈯",
                "name": "u6307"
            },
            {
                "char": "🉐",
                "name": "ideograph_advantage"
            },
            {
                "char": "🈹",
                "name": "u5272"
            },
            {
                "char": "🈚",
                "name": "u7121"
            },
            {
                "char": "🈲",
                "name": "u7981"
            },
            {
                "char": "🉑",
                "name": "accept"
            },
            {
                "char": "🈸",
                "name": "u7533"
            },
            {
                "char": "🈴",
                "name": "u5408"
            },
            {
                "char": "🈳",
                "name": "u7a7a"
            },
            {
                "char": "㊗️",
                "name": "congratulations"
            },
            {
                "char": "㊙️",
                "name": "secret"
            },
            {
                "char": "🈺",
                "name": "u55b6"
            },
            {
                "char": "🈵",
                "name": "u6e80"
            },
            {
                "char": "🔴",
                "name": "red_circle"
            },
            {
                "char": "🟠",
                "name": "orange_circle"
            },
            {
                "char": "🟡",
                "name": "yellow_circle"
            },
            {
                "char": "🟢",
                "name": "green_circle"
            },
            {
                "char": "🔵",
                "name": "large_blue_circle"
            },
            {
                "char": "🟣",
                "name": "purple_circle"
            },
            {
                "char": "🟤",
                "name": "brown_circle"
            },
            {
                "char": "⚫",
                "name": "black_circle"
            },
            {
                "char": "⚪",
                "name": "white_circle"
            },
            {
                "char": "🟥",
                "name": "red_square"
            },
            {
                "char": "🟧",
                "name": "orange_square"
            },
            {
                "char": "🟨",
                "name": "yellow_square"
            },
            {
                "char": "🟩",
                "name": "green_square"
            },
            {
                "char": "🟦",
                "name": "blue_square"
            },
            {
                "char": "🟪",
                "name": "purple_square"
            },
            {
                "char": "🟫",
                "name": "brown_square"
            },
            {
                "char": "⬛",
                "name": "black_large_square"
            },
            {
                "char": "⬜",
                "name": "white_large_square"
            },
            {
                "char": "◼️",
                "name": "black_medium_square"
            },
            {
                "char": "◻️",
                "name": "white_medium_square"
            },
            {
                "char": "◾",
                "name": "black_medium_small_square"
            },
            {
                "char": "◽",
                "name": "white_medium_small_square"
            },
            {
                "char": "▪️",
                "name": "black_small_square"
            },
            {
                "char": "▫️",
                "name": "white_small_square"
            },
            {
                "char": "🔶",
                "name": "large_orange_diamond"
            },
            {
                "char": "🔷",
                "name": "large_blue_diamond"
            },
            {
                "char": "🔸",
                "name": "small_orange_diamond"
            },
            {
                "char": "🔹",
                "name": "small_blue_diamond"
            },
            {
                "char": "🔺",
                "name": "small_red_triangle"
            },
            {
                "char": "🔻",
                "name": "small_red_triangle_down"
            },
            {
                "char": "💠",
                "name": "diamond_shape_with_a_dot_inside"
            },
            {
                "char": "🔘",
                "name": "radio_button"
            },
            {
                "char": "🔳",
                "name": "white_square_button"
            },
            {
                "char": "🔲",
                "name": "black_square_button"
            }
        ]
    },
    {
        "id": "flags",
        "icon": "bi-flag",
        "title": "Flags",
        "items": [
            {
                "char": "🏁",
                "name": "checkered_flag"
            },
            {
                "char": "🚩",
                "name": "triangular_flag_on_post"
            },
            {
                "char": "🎌",
                "name": "crossed_flags"
            },
            {
                "char": "🏴",
                "name": "black_flag"
            },
            {
                "char": "🏳️",
                "name": "white_flag"
            },
            {
                "char": "🏳️‍🌈",
                "name": "rainbow_flag"
            },
            {
                "char": "🏳️‍⚧️",
                "name": "transgender_flag"
            },
            {
                "char": "🏴‍☠️",
                "name": "pirate_flag"
            },
            {
                "char": "🇦🇨",
                "name": "ascension_island"
            },
            {
                "char": "🇦🇩",
                "name": "andorra"
            },
            {
                "char": "🇦🇪",
                "name": "united_arab_emirates"
            },
            {
                "char": "🇦🇫",
                "name": "afghanistan"
            },
            {
                "char": "🇦🇬",
                "name": "antigua_barbuda"
            },
            {
                "char": "🇦🇮",
                "name": "anguilla"
            },
            {
                "char": "🇦🇱",
                "name": "albania"
            },
            {
                "char": "🇦🇲",
                "name": "armenia"
            },
            {
                "char": "🇦🇴",
                "name": "angola"
            },
            {
                "char": "🇦🇶",
                "name": "antarctica"
            },
            {
                "char": "🇦🇷",
                "name": "argentina"
            },
            {
                "char": "🇦🇸",
                "name": "american_samoa"
            },
            {
                "char": "🇦🇹",
                "name": "austria"
            },
            {
                "char": "🇦🇺",
                "name": "australia"
            },
            {
                "char": "🇦🇼",
                "name": "aruba"
            },
            {
                "char": "🇦🇽",
                "name": "aland_islands"
            },
            {
                "char": "🇦🇿",
                "name": "azerbaijan"
            },
            {
                "char": "🇧🇦",
                "name": "bosnia_herzegovina"
            },
            {
                "char": "🇧🇧",
                "name": "barbados"
            },
            {
                "char": "🇧🇩",
                "name": "bangladesh"
            },
            {
                "char": "🇧🇪",
                "name": "belgium"
            },
            {
                "char": "🇧🇫",
                "name": "burkina_faso"
            },
            {
                "char": "🇧🇬",
                "name": "bulgaria"
            },
            {
                "char": "🇧🇭",
                "name": "bahrain"
            },
            {
                "char": "🇧🇮",
                "name": "burundi"
            },
            {
                "char": "🇧🇯",
                "name": "benin"
            },
            {
                "char": "🇧🇱",
                "name": "st_barthelemy"
            },
            {
                "char": "🇧🇲",
                "name": "bermuda"
            },
            {
                "char": "🇧🇳",
                "name": "brunei"
            },
            {
                "char": "🇧🇴",
                "name": "bolivia"
            },
            {
                "char": "🇧🇶",
                "name": "caribbean_netherlands"
            },
            {
                "char": "🇧🇷",
                "name": "brazil"
            },
            {
                "char": "🇧🇸",
                "name": "bahamas"
            },
            {
                "char": "🇧🇹",
                "name": "bhutan"
            },
            {
                "char": "🇧🇻",
                "name": "bouvet_island"
            },
            {
                "char": "🇧🇼",
                "name": "botswana"
            },
            {
                "char": "🇧🇾",
                "name": "belarus"
            },
            {
                "char": "🇧🇿",
                "name": "belize"
            },
            {
                "char": "🇨🇦",
                "name": "canada"
            },
            {
                "char": "🇨🇨",
                "name": "cocos_islands"
            },
            {
                "char": "🇨🇩",
                "name": "congo_kinshasa"
            },
            {
                "char": "🇨🇫",
                "name": "central_african_republic"
            },
            {
                "char": "🇨🇬",
                "name": "congo_brazzaville"
            },
            {
                "char": "🇨🇭",
                "name": "switzerland"
            },
            {
                "char": "🇨🇮",
                "name": "cote_divoire"
            },
            {
                "char": "🇨🇰",
                "name": "cook_islands"
            },
            {
                "char": "🇨🇱",
                "name": "chile"
            },
            {
                "char": "🇨🇲",
                "name": "cameroon"
            },
            {
                "char": "🇨🇳",
                "name": "cn"
            },
            {
                "char": "🇨🇴",
                "name": "colombia"
            },
            {
                "char": "🇨🇵",
                "name": "clipperton_island"
            },
            {
                "char": "🇨🇷",
                "name": "costa_rica"
            },
            {
                "char": "🇨🇺",
                "name": "cuba"
            },
            {
                "char": "🇨🇻",
                "name": "cape_verde"
            },
            {
                "char": "🇨🇼",
                "name": "curacao"
            },
            {
                "char": "🇨🇽",
                "name": "christmas_island"
            },
            {
                "char": "🇨🇾",
                "name": "cyprus"
            },
            {
                "char": "🇨🇿",
                "name": "czech_republic"
            },
            {
                "char": "🇩🇪",
                "name": "de"
            },
            {
                "char": "🇩🇬",
                "name": "diego_garcia"
            },
            {
                "char": "🇩🇯",
                "name": "djibouti"
            },
            {
                "char": "🇩🇰",
                "name": "denmark"
            },
            {
                "char": "🇩🇲",
                "name": "dominica"
            },
            {
                "char": "🇩🇴",
                "name": "dominican_republic"
            },
            {
                "char": "🇩🇿",
                "name": "algeria"
            },
            {
                "char": "🇪🇦",
                "name": "ceuta_melilla"
            },
            {
                "char": "🇪🇨",
                "name": "ecuador"
            },
            {
                "char": "🇪🇪",
                "name": "estonia"
            },
            {
                "char": "🇪🇬",
                "name": "egypt"
            },
            {
                "char": "🇪🇭",
                "name": "western_sahara"
            },
            {
                "char": "🇪🇷",
                "name": "eritrea"
            },
            {
                "char": "🇪🇸",
                "name": "es"
            },
            {
                "char": "🇪🇹",
                "name": "ethiopia"
            },
            {
                "char": "🇪🇺",
                "name": "eu"
            },
            {
                "char": "🇫🇮",
                "name": "finland"
            },
            {
                "char": "🇫🇯",
                "name": "fiji"
            },
            {
                "char": "🇫🇰",
                "name": "falkland_islands"
            },
            {
                "char": "🇫🇲",
                "name": "micronesia"
            },
            {
                "char": "🇫🇴",
                "name": "faroe_islands"
            },
            {
                "char": "🇫🇷",
                "name": "fr"
            },
            {
                "char": "🇬🇦",
                "name": "gabon"
            },
            {
                "char": "🇬🇧",
                "name": "gb"
            },
            {
                "char": "🇬🇩",
                "name": "grenada"
            },
            {
                "char": "🇬🇪",
                "name": "georgia"
            },
            {
                "char": "🇬🇫",
                "name": "french_guiana"
            },
            {
                "char": "🇬🇬",
                "name": "guernsey"
            },
            {
                "char": "🇬🇭",
                "name": "ghana"
            },
            {
                "char": "🇬🇮",
                "name": "gibraltar"
            },
            {
                "char": "🇬🇱",
                "name": "greenland"
            },
            {
                "char": "🇬🇲",
                "name": "gambia"
            },
            {
                "char": "🇬🇳",
                "name": "guinea"
            },
            {
                "char": "🇬🇵",
                "name": "guadeloupe"
            },
            {
                "char": "🇬🇶",
                "name": "equatorial_guinea"
            },
            {
                "char": "🇬🇷",
                "name": "greece"
            },
            {
                "char": "🇬🇸",
                "name": "south_georgia_south_sandwich_islands"
            },
            {
                "char": "🇬🇹",
                "name": "guatemala"
            },
            {
                "char": "🇬🇺",
                "name": "guam"
            },
            {
                "char": "🇬🇼",
                "name": "guinea_bissau"
            },
            {
                "char": "🇬🇾",
                "name": "guyana"
            },
            {
                "char": "🇭🇰",
                "name": "hong_kong"
            },
            {
                "char": "🇭🇲",
                "name": "heard_mcdonald_islands"
            },
            {
                "char": "🇭🇳",
                "name": "honduras"
            },
            {
                "char": "🇭🇷",
                "name": "croatia"
            },
            {
                "char": "🇭🇹",
                "name": "haiti"
            },
            {
                "char": "🇭🇺",
                "name": "hungary"
            },
            {
                "char": "🇮🇨",
                "name": "canary_islands"
            },
            {
                "char": "🇮🇩",
                "name": "indonesia"
            },
            {
                "char": "🇮🇪",
                "name": "ireland"
            },
            {
                "char": "🇮🇱",
                "name": "israel"
            },
            {
                "char": "🇮🇲",
                "name": "isle_of_man"
            },
            {
                "char": "🇮🇳",
                "name": "india"
            },
            {
                "char": "🇮🇴",
                "name": "british_indian_ocean_territory"
            },
            {
                "char": "🇮🇶",
                "name": "iraq"
            },
            {
                "char": "🇮🇷",
                "name": "iran"
            },
            {
                "char": "🇮🇸",
                "name": "iceland"
            },
            {
                "char": "🇮🇹",
                "name": "it"
            },
            {
                "char": "🇯🇪",
                "name": "jersey"
            },
            {
                "char": "🇯🇲",
                "name": "jamaica"
            },
            {
                "char": "🇯🇴",
                "name": "jordan"
            },
            {
                "char": "🇯🇵",
                "name": "jp"
            },
            {
                "char": "🇰🇪",
                "name": "kenya"
            },
            {
                "char": "🇰🇬",
                "name": "kyrgyzstan"
            },
            {
                "char": "🇰🇭",
                "name": "cambodia"
            },
            {
                "char": "🇰🇮",
                "name": "kiribati"
            },
            {
                "char": "🇰🇲",
                "name": "comoros"
            },
            {
                "char": "🇰🇳",
                "name": "st_kitts_nevis"
            },
            {
                "char": "🇰🇵",
                "name": "north_korea"
            },
            {
                "char": "🇰🇷",
                "name": "kr"
            },
            {
                "char": "🇰🇼",
                "name": "kuwait"
            },
            {
                "char": "🇰🇾",
                "name": "cayman_islands"
            },
            {
                "char": "🇰🇿",
                "name": "kazakhstan"
            },
            {
                "char": "🇱🇦",
                "name": "laos"
            },
            {
                "char": "🇱🇧",
                "name": "lebanon"
            },
            {
                "char": "🇱🇨",
                "name": "st_lucia"
            },
            {
                "char": "🇱🇮",
                "name": "liechtenstein"
            },
            {
                "char": "🇱🇰",
                "name": "sri_lanka"
            },
            {
                "char": "🇱🇷",
                "name": "liberia"
            },
            {
                "char": "🇱🇸",
                "name": "lesotho"
            },
            {
                "char": "🇱🇹",
                "name": "lithuania"
            },
            {
                "char": "🇱🇺",
                "name": "luxembourg"
            },
            {
                "char": "🇱🇻",
                "name": "latvia"
            },
            {
                "char": "🇱🇾",
                "name": "libya"
            },
            {
                "char": "🇲🇦",
                "name": "morocco"
            },
            {
                "char": "🇲🇨",
                "name": "monaco"
            },
            {
                "char": "🇲🇩",
                "name": "moldova"
            },
            {
                "char": "🇲🇪",
                "name": "montenegro"
            },
            {
                "char": "🇲🇫",
                "name": "st_martin"
            },
            {
                "char": "🇲🇬",
                "name": "madagascar"
            },
            {
                "char": "🇲🇭",
                "name": "marshall_islands"
            },
            {
                "char": "🇲🇰",
                "name": "macedonia"
            },
            {
                "char": "🇲🇱",
                "name": "mali"
            },
            {
                "char": "🇲🇲",
                "name": "myanmar"
            },
            {
                "char": "🇲🇳",
                "name": "mongolia"
            },
            {
                "char": "🇲🇴",
                "name": "macau"
            },
            {
                "char": "🇲🇵",
                "name": "northern_mariana_islands"
            },
            {
                "char": "🇲🇶",
                "name": "martinique"
            },
            {
                "char": "🇲🇷",
                "name": "mauritania"
            },
            {
                "char": "🇲🇸",
                "name": "montserrat"
            },
            {
                "char": "🇲🇹",
                "name": "malta"
            },
            {
                "char": "🇲🇺",
                "name": "mauritius"
            },
            {
                "char": "🇲🇻",
                "name": "maldives"
            },
            {
                "char": "🇲🇼",
                "name": "malawi"
            },
            {
                "char": "🇲🇽",
                "name": "mexico"
            },
            {
                "char": "🇲🇾",
                "name": "malaysia"
            },
            {
                "char": "🇲🇿",
                "name": "mozambique"
            },
            {
                "char": "🇳🇦",
                "name": "namibia"
            },
            {
                "char": "🇳🇨",
                "name": "new_caledonia"
            },
            {
                "char": "🇳🇪",
                "name": "niger"
            },
            {
                "char": "🇳🇫",
                "name": "norfolk_island"
            },
            {
                "char": "🇳🇬",
                "name": "nigeria"
            },
            {
                "char": "🇳🇮",
                "name": "nicaragua"
            },
            {
                "char": "🇳🇱",
                "name": "netherlands"
            },
            {
                "char": "🇳🇴",
                "name": "norway"
            },
            {
                "char": "🇳🇵",
                "name": "nepal"
            },
            {
                "char": "🇳🇷",
                "name": "nauru"
            },
            {
                "char": "🇳🇺",
                "name": "niue"
            },
            {
                "char": "🇳🇿",
                "name": "new_zealand"
            },
            {
                "char": "🇴🇲",
                "name": "oman"
            },
            {
                "char": "🇵🇦",
                "name": "panama"
            },
            {
                "char": "🇵🇪",
                "name": "peru"
            },
            {
                "char": "🇵🇫",
                "name": "french_polynesia"
            },
            {
                "char": "🇵🇬",
                "name": "papua_new_guinea"
            },
            {
                "char": "🇵🇭",
                "name": "philippines"
            },
            {
                "char": "🇵🇰",
                "name": "pakistan"
            },
            {
                "char": "🇵🇱",
                "name": "poland"
            },
            {
                "char": "🇵🇲",
                "name": "st_pierre_miquelon"
            },
            {
                "char": "🇵🇳",
                "name": "pitcairn_islands"
            },
            {
                "char": "🇵🇷",
                "name": "puerto_rico"
            },
            {
                "char": "🇵🇸",
                "name": "palestinian_territories"
            },
            {
                "char": "🇵🇹",
                "name": "portugal"
            },
            {
                "char": "🇵🇼",
                "name": "palau"
            },
            {
                "char": "🇵🇾",
                "name": "paraguay"
            },
            {
                "char": "🇶🇦",
                "name": "qatar"
            },
            {
                "char": "🇷🇪",
                "name": "reunion"
            },
            {
                "char": "🇷🇴",
                "name": "romania"
            },
            {
                "char": "🇷🇸",
                "name": "serbia"
            },
            {
                "char": "🇷🇺",
                "name": "ru"
            },
            {
                "char": "🇷🇼",
                "name": "rwanda"
            },
            {
                "char": "🇸🇦",
                "name": "saudi_arabia"
            },
            {
                "char": "🇸🇧",
                "name": "solomon_islands"
            },
            {
                "char": "🇸🇨",
                "name": "seychelles"
            },
            {
                "char": "🇸🇩",
                "name": "sudan"
            },
            {
                "char": "🇸🇪",
                "name": "sweden"
            },
            {
                "char": "🇸🇬",
                "name": "singapore"
            },
            {
                "char": "🇸🇭",
                "name": "st_helena"
            },
            {
                "char": "🇸🇮",
                "name": "slovenia"
            },
            {
                "char": "🇸🇯",
                "name": "svalbard_jan_mayen"
            },
            {
                "char": "🇸🇰",
                "name": "slovakia"
            },
            {
                "char": "🇸🇱",
                "name": "sierra_leone"
            },
            {
                "char": "🇸🇲",
                "name": "san_marino"
            },
            {
                "char": "🇸🇳",
                "name": "senegal"
            },
            {
                "char": "🇸🇴",
                "name": "somalia"
            },
            {
                "char": "🇸🇷",
                "name": "suriname"
            },
            {
                "char": "🇸🇸",
                "name": "south_sudan"
            },
            {
                "char": "🇸🇹",
                "name": "sao_tome_principe"
            },
            {
                "char": "🇸🇻",
                "name": "el_salvador"
            },
            {
                "char": "🇸🇽",
                "name": "sint_maarten"
            },
            {
                "char": "🇸🇾",
                "name": "syria"
            },
            {
                "char": "🇸🇿",
                "name": "swaziland"
            },
            {
                "char": "🇹🇦",
                "name": "tristan_da_cunha"
            },
            {
                "char": "🇹🇨",
                "name": "turks_caicos_islands"
            },
            {
                "char": "🇹🇩",
                "name": "chad"
            },
            {
                "char": "🇹🇫",
                "name": "french_southern_territories"
            },
            {
                "char": "🇹🇬",
                "name": "togo"
            },
            {
                "char": "🇹🇭",
                "name": "thailand"
            },
            {
                "char": "🇹🇯",
                "name": "tajikistan"
            },
            {
                "char": "🇹🇰",
                "name": "tokelau"
            },
            {
                "char": "🇹🇱",
                "name": "timor_leste"
            },
            {
                "char": "🇹🇲",
                "name": "turkmenistan"
            },
            {
                "char": "🇹🇳",
                "name": "tunisia"
            },
            {
                "char": "🇹🇴",
                "name": "tonga"
            },
            {
                "char": "🇹🇷",
                "name": "tr"
            },
            {
                "char": "🇹🇹",
                "name": "trinidad_tobago"
            },
            {
                "char": "🇹🇻",
                "name": "tuvalu"
            },
            {
                "char": "🇹🇼",
                "name": "taiwan"
            },
            {
                "char": "🇹🇿",
                "name": "tanzania"
            },
            {
                "char": "🇺🇦",
                "name": "ukraine"
            },
            {
                "char": "🇺🇬",
                "name": "uganda"
            },
            {
                "char": "🇺🇲",
                "name": "us_outlying_islands"
            },
            {
                "char": "🇺🇳",
                "name": "united_nations"
            },
            {
                "char": "🇺🇸",
                "name": "us"
            },
            {
                "char": "🇺🇾",
                "name": "uruguay"
            },
            {
                "char": "🇺🇿",
                "name": "uzbekistan"
            },
            {
                "char": "🇻🇦",
                "name": "vatican_city"
            },
            {
                "char": "🇻🇨",
                "name": "st_vincent_grenadines"
            },
            {
                "char": "🇻🇪",
                "name": "venezuela"
            },
            {
                "char": "🇻🇬",
                "name": "british_virgin_islands"
            },
            {
                "char": "🇻🇮",
                "name": "us_virgin_islands"
            },
            {
                "char": "🇻🇳",
                "name": "vietnam"
            },
            {
                "char": "🇻🇺",
                "name": "vanuatu"
            },
            {
                "char": "🇼🇫",
                "name": "wallis_futuna"
            },
            {
                "char": "🇼🇸",
                "name": "samoa"
            },
            {
                "char": "🇽🇰",
                "name": "kosovo"
            },
            {
                "char": "🇾🇪",
                "name": "yemen"
            },
            {
                "char": "🇾🇹",
                "name": "mayotte"
            },
            {
                "char": "🇿🇦",
                "name": "south_africa"
            },
            {
                "char": "🇿🇲",
                "name": "zambia"
            },
            {
                "char": "🇿🇼",
                "name": "zimbabwe"
            },
            {
                "char": "🏴󠁧󠁢󠁥󠁮󠁧󠁿",
                "name": "england"
            },
            {
                "char": "🏴󠁧󠁢󠁳󠁣󠁴󠁿",
                "name": "scotland"
            },
            {
                "char": "🏴󠁧󠁢󠁷󠁬󠁳󠁿",
                "name": "wales"
            }
        ]
    }
];

const stickersData = [
    {
        "id": "dino",
        "title": "Dinosaur Pack",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f996/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f996/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f40a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f432/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f98e/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f40d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f422/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f98a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f995/512.gif"
        ]
    },
    {
        "id": "monkeys",
        "title": "Monkeys",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f98d/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f98d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f435/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f648/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f649/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f64a/512.gif"
        ]
    },
    {
        "id": "cats",
        "title": "Cats",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63b/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63b/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f638/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f639/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63c/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63e/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f63f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f640/512.gif"
        ]
    },
    {
        "id": "dogs",
        "title": "Dogs & Wolves",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f415/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f415/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f9ae/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f429/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f43a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f98a/512.gif"
        ]
    },
    {
        "id": "gestures",
        "title": "Hand Gestures",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f44d/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f44d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f44e/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f44f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f44b/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f4aa/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/270c_fe0f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f64f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f91d/512.gif"
        ]
    },
    {
        "id": "food",
        "title": "Food & Drink",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f34e/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f34e/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f352/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f353/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f349/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f34a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f354/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f35f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f355/512.gif"
        ]
    },
    {
        "id": "love",
        "title": "Love & Hearts",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/2764_fe0f/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/2764_fe0f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f9e1/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f49b/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f49a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f499/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f49c/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f49d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f49e/512.gif"
        ]
    },
    {
        "id": "expressions",
        "title": "Expressions",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f92f/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f92f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f602/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f923/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f62d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f631/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f621/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f914/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f92a/512.gif"
        ]
    },
    {
        "id": "weather",
        "title": "Weather & Space",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f31e/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f31e/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f319/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/2b50/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f525/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/2601_fe0f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/26a1/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/2744_fe0f/512.gif"
        ]
    },
    {
        "id": "sports",
        "title": "Sports & Games",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/26bd/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/26bd/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3c0/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3be/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3c6/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3ae/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3b1/512.gif"
        ]
    },
    {
        "id": "magic",
        "title": "Fantasy & Magic",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f47d/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f47d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f47b/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f480/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f916/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f984/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f9db/512.gif"
        ]
    },
    {
        "id": "vehicles",
        "title": "Travel & Vehicles",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f697/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f697/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f680/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/2708_fe0f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f682/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/26f5/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f6f8/512.gif"
        ]
    },
    {
        "id": "plants",
        "title": "Plants & Nature",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f335/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f335/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f339/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f33b/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f331/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f332/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f333/512.gif"
        ]
    },
    {
        "id": "office",
        "title": "Office & Writing",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f4bb/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f4bb/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f4d6/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/270f_fe0f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f4bc/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f4c5/512.gif"
        ]
    },
    {
        "id": "music",
        "title": "Musical Instruments",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3b8/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3b8/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3ba/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3da_fe0f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3bb/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f3b9/512.gif"
        ]
    },
    {
        "id": "marine",
        "title": "Marine Life",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f41f/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f41f/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f433/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f42c/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f419/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f41a/512.gif"
        ]
    },
    {
        "id": "expressions2",
        "title": "Faces & Moods",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f60e/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f60e/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f973/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f97a/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f92c/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f920/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f607/512.gif"
        ]
    },
    {
        "id": "animals2",
        "title": "Farm & Reptiles",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f422/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f422/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f40d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f414/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f411/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f416/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f42d/512.gif"
        ]
    },
    {
        "id": "spooky",
        "title": "Spooky & Scary",
        "icon": "https://fonts.gstatic.com/s/e/notoemoji/latest/1f383/512.gif",
        "items": [
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f383/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f47b/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f47d/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f9df/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f480/512.gif",
            "https://fonts.gstatic.com/s/e/notoemoji/latest/1f577_fe0f/512.gif"
        ]
    }
];

const gifsData = [
    {
        "id": "funny",
        "title": "Funny",
        "icon": "bi-emoji-laughing",
        "items": [
            "https://media.tenor.com/HXHCV0LpqNAAAAAC/hilarious-so-funny.gif",
            "https://media.tenor.com/5ot5ADGxJdAAAAAC/hello.gif",
            "https://media.tenor.com/DjYEAU0fjNsAAAAC/laughing-baby.gif",
            "https://media.tenor.com/nJf6TOpJVO0AAAAC/belly-bounce.gif",
            "https://media.tenor.com/d-m-p0EQpTcAAAAC/monkey-ape.gif",
            "https://media.tenor.com/vsVkmVQDwAQAAAAC/baby-bear-noticing.gif",
            "https://media.tenor.com/4aoemls76hAAAAAC/fufu.gif",
            "https://media.tenor.com/49G7Vh1ciuMAAAAC/hi-silly.gif",
            "https://media.tenor.com/7nFFLj6sjYkAAAAC/not-funny-eye-roll.gif",
            "https://media.tenor.com/HtwX9GAhZR0AAAAC/puppy-puppy-eyes.gif",
            "https://media.tenor.com/GVF-jXDy918AAAAC/taylor-swift-monkey.gif",
            "https://media.tenor.com/ixsibLgVCW4AAAAC/heyy-sandwich.gif",
            "https://media.tenor.com/HBFdQJHzaX8AAAAC/jim-carrey-funny.gif",
            "https://media.tenor.com/ND-WDncsB6oAAAAC/laughing-laugh.gif",
            "https://media.tenor.com/fnVo42SgddYAAAAC/dog.gif",
            "https://media.tenor.com/PANTOgRQN64AAAAC/bizg-rire.gif"
        ]
    },
    {
        "id": "reactions",
        "title": "Reactions",
        "icon": "bi-lightning",
        "items": [
            "https://media.tenor.com/jc6uJ-f5jocAAAAC/skull-reacts-skull.gif",
            "https://media.tenor.com/rxR4IuhQU1oAAAAC/blu-zushi-black-and-white.gif",
            "https://media.tenor.com/FzmSikOv1YkAAAAC/shadetree-alex.gif",
            "https://media.tenor.com/KXjLtkl8brAAAAAC/janyelix-jany.gif",
            "https://media.tenor.com/E5i0gMKfK64AAAAC/gots-gots-gaming.gif",
            "https://media.tenor.com/INWksq7Jks4AAAAC/gots-gots-gaming.gif",
            "https://media.tenor.com/EdlT5CBGhJsAAAAC/tubbo-no-dice.gif",
            "https://media.tenor.com/_uxUNRgJlfgAAAAC/my-honest-reaction.gif",
            "https://media.tenor.com/kT9Jg9z1bKIAAAAC/barn-owl-owl.gif",
            "https://media.tenor.com/J5Mi2Ix3kUUAAAAC/honest-reaction-reaction.gif",
            "https://media.tenor.com/5s2c6vxhbDsAAAAC/big-eyes-yippee.gif",
            "https://media.tenor.com/xPDSb97K2bgAAAAC/my-honest-reaction-my-honest-reaction-meme.gif",
            "https://media.tenor.com/KuNGkRnNRJ0AAAAC/my-honest-reaction-my-reaction.gif",
            "https://media.tenor.com/Dv_KUlnY2tEAAAAC/my-honest-reaction-my-reaction-to-that-information.gif",
            "https://media.tenor.com/M85jBN5fHnMAAAAC/jbb-withc.gif",
            "https://media.tenor.com/JrLvI2rgTL4AAAAC/omori-sunny.gif"
        ]
    },
    {
        "id": "animals",
        "title": "Animals",
        "icon": "bi-bug",
        "items": [
            "https://media.tenor.com/-8TmOhlDn6sAAAAC/having-a-snack.gif",
            "https://media.tenor.com/zNHvXafUyEoAAAAC/i-love-animals-animals.gif",
            "https://media.tenor.com/TTRUM1afMRUAAAAC/roy-bango-roy.gif",
            "https://media.tenor.com/pvVNUgTEEnYAAAAC/tired-baby-cute.gif",
            "https://media.tenor.com/2KKE0rJo-koAAAAC/freedom.gif",
            "https://media.tenor.com/J0HwLVvD9MEAAAAC/rabit-cute-animals.gif",
            "https://media.tenor.com/MMiqhNS-jE8AAAAC/capybara-chewing.gif",
            "https://media.tenor.com/g26gsy9UPmwAAAAC/1.gif",
            "https://media.tenor.com/xAgxhjvW-g8AAAAC/happy-animal-cute-animals.gif",
            "https://media.tenor.com/IXme6iNB8RMAAAAC/horse-whip-my-hair.gif",
            "https://media.tenor.com/adf8IAybbwgAAAAC/lemur-eye.gif",
            "https://media.tenor.com/Z8Xc-rp6APIAAAAC/cat.gif",
            "https://media.tenor.com/nsvIcbammIAAAAAC/screaming-fox.gif",
            "https://media.tenor.com/MaNQZ38ofmkAAAAC/happy-birthday-ashleigh.gif",
            "https://media.tenor.com/O7mgkRPdq0YAAAAC/animals.gif"
        ]
    },
    {
        "id": "memes",
        "title": "Memes",
        "icon": "bi-image",
        "items": [
            "https://media.tenor.com/MobHB2lzzFYAAAAC/gethomered.gif",
            "https://media.tenor.com/rvdvCpXlGRQAAAAC/rat-mouse.gif",
            "https://media.tenor.com/2kt3dYeQNmwAAAAC/dancing-dog-dog.gif",
            "https://media.tenor.com/31044VVA7iMAAAAC/mewing-cat.gif",
            "https://media.tenor.com/s0TPypKl6J4AAAAC/tuff-tuff-baby.gif",
            "https://media.tenor.com/QjYGc4WGV1QAAAAC/kittycore-cat.gif",
            "https://media.tenor.com/te8Hwtha-EoAAAAC/shrek-rizz-shrek-meme.gif",
            "https://media.tenor.com/ixMmJyhMZokAAAAC/zoogle-cat.gif",
            "https://media.tenor.com/Ob1fIszBHjEAAAAC/roblox-memes-roblox.gif",
            "https://media.tenor.com/rEbWhkNfVOkAAAAC/gato-cora%C3%A7%C3%A3o.gif",
            "https://media.tenor.com/E2IsXipYZOYAAAAC/super-mario-bros-toy.gif",
            "https://media.tenor.com/zym9t648lewAAAAC/fubangifs.gif",
            "https://media.tenor.com/8A8oMXU5CZMAAAAC/totr-spongebob.gif",
            "https://media.tenor.com/jOOlYi_9i0EAAAAC/wolf-dancing-meme-wolf-meme.gif",
            "https://media.tenor.com/m7NLU6YmjmQAAAAC/bad-teeth.gif",
            "https://media.tenor.com/tUY7skOcMg8AAAAC/feddy-fazber-freddy-fazbear.gif"
        ]
    },
    {
        "id": "gaming",
        "title": "Gaming",
        "icon": "bi-controller",
        "items": [
            "https://media.tenor.com/jSS--MNKR-wAAAAC/adult-contex-babe-hows-the-gravy-coming.gif",
            "https://media.tenor.com/9OoAmhPHsl4AAAAC/gaming-racing.gif",
            "https://media.tenor.com/_xlkCDuKXasAAAAC/fortnite-south-park.gif",
            "https://media.tenor.com/vK4BBOuyxZIAAAAC/gaming-timmy-turner.gif",
            "https://media.tenor.com/cqNBVT8hdXcAAAAC/%D0%BF%D1%83%D1%88%D0%B8%D0%BD-pusheen.gif",
            "https://media.tenor.com/1Op1SlDy7hUAAAAC/computer-games.gif",
            "https://media.tenor.com/MK5PxS2UPXsAAAAC/cat-gaming.gif",
            "https://media.tenor.com/ezB2vqL2OWAAAAAC/mathurin-maf.gif",
            "https://media.tenor.com/DkreM48B_GoAAAAC/calling-all.gif",
            "https://media.tenor.com/IJuv2EOqRPUAAAAC/ashton-hall-ashton.gif",
            "https://media.tenor.com/BakMrb7FsP8AAAAC/playing-video-games-smoothie.gif",
            "https://media.tenor.com/6afWhPVV8uAAAAAC/gaming-ishotz.gif",
            "https://media.tenor.com/taKLUGBJbKcAAAAC/gaming.gif",
            "https://media.tenor.com/E5Wq3fldSPIAAAAC/south-park-eric.gif",
            "https://media.tenor.com/bmtZHP80P7kAAAAC/tryhard-gamer.gif",
            "https://media.tenor.com/SWpRSqXNWzsAAAAC/gamer-mod.gif"
        ]
    },
    {
        "id": "sports",
        "title": "Sports",
        "icon": "bi-dribbble",
        "items": [
            "https://media.tenor.com/LrDoTxlDORsAAAAC/patrick-star-spongebob-squarepants.gif",
            "https://media.tenor.com/LP9ZjRdYClYAAAAC/sorry.gif",
            "https://media.tenor.com/zVpEUPeKgTwAAAAC/go-team-go-sports.gif",
            "https://media.tenor.com/qX_eL3Q3t5sAAAAC/nyan-nyanx.gif",
            "https://media.tenor.com/M4OW-ShWKccAAAAC/wit-deal-with-it.gif",
            "https://media.tenor.com/TbmAoqdN_WoAAAAC/fail-woman.gif",
            "https://media.tenor.com/8foLUCKsr2kAAAAC/nonono-sports.gif",
            "https://media.tenor.com/VCBEtkSryT4AAAAC/playsports-jupilerproleague.gif",
            "https://media.tenor.com/L2f33izq61gAAAAC/sports.gif",
            "https://media.tenor.com/neTIJruDJ2YAAAAC/%2A%2A%2Aodidos-james-rodr%C3%ADguez.gif",
            "https://media.tenor.com/LwQfKW1-dDkAAAAC/sports-sport.gif",
            "https://media.tenor.com/t1lmaNhBJW0AAAAC/simpsons-homer.gif",
            "https://media.tenor.com/keJ3GlC8dyUAAAAC/matnpamda-james-rodr%C3%ADguez.gif",
            "https://media.tenor.com/NHokc5x8pGgAAAAC/celtics-superfan.gif",
            "https://media.tenor.com/oQZdUO4Z-WwAAAAC/hascord-3headsports.gif",
            "https://media.tenor.com/OXokCK-YmusAAAAC/basketball-dunk.gif"
        ]
    },
    {
        "id": "movies",
        "title": "Movies",
        "icon": "bi-camera-reels",
        "items": [
            "https://media.tenor.com/GNT5qaiNYMEAAAAC/watch-watching.gif",
            "https://media.tenor.com/ZYb77l1oH2cAAAAC/pengu-pudgy.gif",
            "https://media.tenor.com/ERWdXcRP164AAAAC/popcorn-eating-tina-fey.gif",
            "https://media.tenor.com/pFuNNH6LO7cAAAAC/munchies-420.gif",
            "https://media.tenor.com/MhkXSkSv-soAAAAC/scary-movie.gif",
            "https://media.tenor.com/S1r_YTIOtKgAAAAC/movie-bored.gif",
            "https://media.tenor.com/zDZRlH-tT1sAAAAC/despicable-me-minions.gif",
            "https://media.tenor.com/CxV4BqcXACUAAAAC/movies-candy.gif",
            "https://media.tenor.com/u6sXwGEqbHAAAAAC/godfather-the.gif",
            "https://media.tenor.com/5KF3BqrpKs8AAAAC/eating-popcorn-watching-a-movie.gif",
            "https://media.tenor.com/MuieBKD728EAAAAC/snoopy-relax.gif",
            "https://media.tenor.com/HUbQhjFS3kcAAAAC/movie-time-mov.gif",
            "https://media.tenor.com/gsOKH4kl104AAAAC/abell46s-reface.gif",
            "https://media.tenor.com/Y8KOj1DPatQAAAAC/martin-scorsese-absolute-cinema.gif",
            "https://media.tenor.com/0i2STPNl0GMAAAAC/movie-time-movie-night.gif",
            "https://media.tenor.com/lxftMq3V-zIAAAAC/movie-time-movie.gif"
        ]
    },
    {
        "id": "anime",
        "title": "Anime",
        "icon": "bi-balloon-heart",
        "items": [
            "https://media.tenor.com/Lhwo0gmSWLcAAAAC/higuruma-jjk.gif",
            "https://media.tenor.com/ukUeo6XcejEAAAAC/osaka-spin.gif",
            "https://media.tenor.com/P7hCyZlzDH4AAAAC/wink-anime.gif",
            "https://media.tenor.com/i1CvyMNnCX0AAAAC/jojos-bizarre-adventure-jjba.gif",
            "https://media.tenor.com/AAMEFNsRaeEAAAAC/anime-girl.gif",
            "https://media.tenor.com/UKNb_Bp4uPwAAAAC/apothecary-diaries-anime.gif",
            "https://media.tenor.com/2ZuUWp5LDfIAAAAC/konata-lucky-star.gif",
            "https://media.tenor.com/jY8tXM6lf50AAAAC/anime-spy-x-family.gif",
            "https://media.tenor.com/b0ZXAm867pYAAAAC/jujutsu-kaisen-season-3.gif",
            "https://media.tenor.com/McrQ8zloGp4AAAAC/anime.gif",
            "https://media.tenor.com/DsOByIMiP6wAAAAC/good-girl-good-girl-meme.gif",
            "https://media.tenor.com/tj-w0D12mqkAAAAC/hy.gif",
            "https://media.tenor.com/I5EGW9OwiG8AAAAC/gojo-finger-bite.gif",
            "https://media.tenor.com/sqLbOKO9o2oAAAAC/good-boy-good-boy-meme.gif",
            "https://media.tenor.com/v-FM8JDUXsoAAAAC/chou-kaguya-hime-kaguya-hime.gif",
            "https://media.tenor.com/Bhq1WZGJfqIAAAAC/frieren-cry-frieren-beyond-journey%27s-end.gif"
        ]
    },
    {
        "id": "sad",
        "title": "Sad",
        "icon": "bi-cloud-rain",
        "items": [
            "https://media.tenor.com/OXAMthjEqm4AAAAC/dog-scroll.gif",
            "https://media.tenor.com/oQ-ffebqvI4AAAAC/plz.gif",
            "https://media.tenor.com/rl_da90w6a0AAAAC/%E7%9A%849.gif",
            "https://media.tenor.com/jv7A2Hj-tBgAAAAC/sad-bubu.gif",
            "https://media.tenor.com/UacnAFq8PhoAAAAC/nub-cat-nub.gif",
            "https://media.tenor.com/C--7FJ2rC-oAAAAC/startamilchat-sanjay-chat.gif",
            "https://media.tenor.com/OroVCOXbuUUAAAAC/sadhamstergirl.gif",
            "https://media.tenor.com/-p6afoyWR_QAAAAC/will-william.gif",
            "https://media.tenor.com/lV1EF4I83MkAAAAC/bubu-dudu-twitter.gif",
            "https://media.tenor.com/pobajegdmmgAAAAC/startamilchat-sanjay-chat.gif",
            "https://media.tenor.com/1moJO32FTGAAAAAC/star-tamil-chat-startamilchat.gif",
            "https://media.tenor.com/8DB9gYKzozUAAAAC/rain-raining.gif",
            "https://media.tenor.com/iEXbs40PJrYAAAAC/heart-broken-broken-heart.gif",
            "https://media.tenor.com/D5JyOlboNAkAAAAC/star-tamil-chat-sanjay-chat.gif",
            "https://media.tenor.com/lMywQp5vRHUAAAAC/cry.gif",
            "https://media.tenor.com/iA-8FovUiVgAAAAC/sad-emoji-man.gif"
        ]
    }
];


const emojiSynonyms = {
    'funny': ['joy', 'rofl', 'laughing', 'grin', 'smiley', 'smile', 'wink', 'stuck_out_tongue', 'zany', 'clown'],
    'laugh': ['joy', 'rofl', 'laughing', 'grin', 'smiley', 'smile', 'wink', 'zany'],
    'lol': ['joy', 'rofl', 'laughing', 'grin', 'smiley', 'smile', 'wink'],
    'joke': ['joy', 'rofl', 'laughing', 'grin', 'smiley', 'smile', 'wink', 'zany', 'clown'],
    'happy': ['smile', 'smiley', 'grin', 'blush', 'joy', 'laughing', 'slight_smile', 'innocent', 'wink', 'yum'],
    'joy': ['smile', 'smiley', 'grin', 'blush', 'joy', 'laughing', 'slight_smile'],
    'glad': ['smile', 'smiley', 'grin', 'blush', 'joy', 'laughing', 'slight_smile'],
    'smile': ['smile', 'smiley', 'grin', 'blush', 'joy', 'laughing', 'slight_smile'],
    'sad': ['pensive', 'sad', 'cry', 'sob', 'broken_heart', 'frown', 'sweat', 'weary', 'disappointed', 'unhappy'],
    'cry': ['cry', 'sob', 'tear', 'sad', 'weary'],
    'sob': ['sob', 'cry', 'sad'],
    'tear': ['cry', 'sob', 'tear', 'sad'],
    'depressed': ['pensive', 'sad', 'cry', 'sob', 'frown', 'weary'],
    'angry': ['angry', 'rage', 'mad', 'frowning', 'imp', 'cursing'],
    'mad': ['angry', 'rage', 'mad', 'frowning', 'imp'],
    'rage': ['angry', 'rage', 'mad', 'frowning', 'imp'],
    'love': ['heart', 'love', 'kiss', 'blush', 'couple', 'heart_eyes', 'smiling_face_with_three_hearts'],
    'heart': ['heart', 'love', 'kiss', 'blush', 'couple', 'heart_eyes', 'smiling_face_with_three_hearts'],
    'kiss': ['kiss', 'kissing'],
    'cool': ['sunglasses', 'cool', 'star_struck', 'fire', 'sparkles'],
    'awesome': ['sunglasses', 'cool', 'star_struck', 'fire', 'sparkles', 'hands'],
    'scared': ['scream', 'fear', 'shock', 'flushed', 'cold_sweat', 'anxious', 'weary'],
    'fear': ['scream', 'fear', 'shock', 'flushed', 'cold_sweat', 'anxious', 'weary'],
    'shock': ['scream', 'fear', 'shock', 'flushed', 'cold_sweat', 'anxious', 'astonished'],
    'surprised': ['astonished', 'screaming', 'hushed', 'open_mouth'],
    'cat': ['cat', 'kitten', 'meow'],
    'dog': ['dog', 'puppy', 'bark'],
    'sun': ['sun', 'sunny', 'bright', 'warm'],
    'fire': ['fire', 'hot', 'lit'],
    'ok': ['ok_hand', 'thumbsup', 'check'],
    'yes': ['ok_hand', 'thumbsup', 'check', 'heavy_check_mark'],
    'no': ['x', 'cross_mark', 'thumbsdown', 'stop'],
    'sick': ['thermometer', 'head_bandage', 'nauseated', 'vomiting', 'sneezing', 'medical_mask', 'mask'],
    'tired': ['sleepy', 'sleeping', 'weary', 'yawn', 'tired_face']
};

const BOOTSTRAP_TO_SVG_MAP = {
    'bi-emoji-smile': 'emoji',
    'bi-person': 'people',
    'bi-bug': 'animals',
    'bi-apple': 'food',
    'bi-car-front': 'travel',
    'bi-bicycle': 'activities',
    'bi-lightbulb': 'objects',
    'bi-hash': 'symbols',
    'bi-flag': 'flags',
    'bi-emoji-laughing': 'funny-gif',
    'bi-lightning': 'reactions',
    'bi-image': 'memes',
    'bi-controller': 'gaming',
    'bi-dribbble': 'sports',
    'bi-camera-reels': 'movies',
    'bi-balloon-heart': 'anime',
    'bi-cloud-rain': 'sad-gif',
    'bi-search': 'search',
    'bi-x-circle-fill': 'close',
    'bi-x-circle': 'x-circle',
    'bi-sun-fill': 'sun',
    'bi-moon-fill': 'moon',
    'bi-sticky': 'sticker',
    'bi-chevron-down': 'chevron-down'
};

const SVG_ICONS = {
    'search': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>`,
    'close': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`,
    'sun': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`,
    'moon': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`,
    'chevron-down': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>`,
    'emoji': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>`,
    'sticker': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9 9 9-9-9-9z"></path><path d="M2 12h10V2"></path></svg>`,
    'x-circle': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`,
    'smileys': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>`,
    'people': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>`,
    'animals': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a5 5 0 0 0-5 5v10a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5z"></path><path d="M6 10h12"></path><path d="M6 14h12"></path><path d="M12 2v20"></path></svg>`,
    'food': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2c3.04 0 5.5 2.46 5.5 5.5S15.04 13 12 13s-5.5-2.46-5.5-5.5S8.96 2 12 2z"></path><path d="M12 2v3"></path></svg>`,
    'travel': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12" y2="18.01"></line></svg>`,
    'activities': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="5.5" cy="17.5" r="3.5"></circle><circle cx="18.5" cy="17.5" r="3.5"></circle><path d="M15 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm-3 5.5V14l-3-3-4 3"></path></svg>`,
    'objects': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .5 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5h6z"></path><line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line></svg>`,
    'symbols': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>`,
    'flags': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>`,
    
    // Gifs Categories
    'funny-gif': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>`,
    'reactions': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>`,
    'memes': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>`,
    'gaming': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><path d="M6 12h4m-2-2v4m7-2h.01m2.99 0h.01"></path></svg>`,
    'sports': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M6 12a6 6 0 0 1 12 0"></path><path d="M12 6a6 6 0 0 1 0 12"></path></svg>`,
    'movies': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>`,
    'anime': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>`,
    'sad-gif': `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 17.58A5 5 0 0 0 18 8h-1.26A8 8 0 1 0 4 16.25"></path><line x1="8" y1="16" x2="8" y2="22"></line><line x1="12" y1="16" x2="12" y2="22"></line><line x1="16" y1="16" x2="16" y2="22"></line></svg>`
};

function getIconSvg(iconName, size) {
    if (!iconName) return '';
    if (iconName.startsWith('http') || iconName.includes('/') || iconName.includes('.')) {
        return `<img src="${iconName}" alt="icon" style="width: ${size || 18}px; height: ${size || 18}px; object-fit: contain;">`;
    }
    const key = BOOTSTRAP_TO_SVG_MAP[iconName] || iconName;
    let svg = SVG_ICONS[key] || '';
    if (svg) {
        if (size) {
            svg = svg.replace('<svg', `<svg width="${size}" height="${size}"`);
        } else {
            svg = svg.replace('<svg', `<svg width="18" height="18"`);
        }
    }
    return svg;
}


class ESGStudio {
    constructor(options = {}) {
        if (window.esgStudioInstance) {
            const instance = window.esgStudioInstance;
            instance.onSelect = options.onSelect || (() => { });

            if (options.trigger) {
                instance.triggerElement = typeof options.trigger === 'string'
                    ? document.querySelector(options.trigger)
                    : options.trigger;
                instance.setupTriggerEvent();
            } else if (window.event) {
                let target = window.event.target;
                while (target && target !== document.body) {
                    if (target.tagName === 'BUTTON' || target.getAttribute('role') === 'button' || target.id === 'togglePickerBtn') {
                        instance.triggerElement = target;
                        break;
                    }
                    target = target.parentElement;
                }
            }

            if (options.theme && !localStorage.getItem('esg-studio-theme')) {
                instance.setTheme(options.theme);
            }

            // Dynamically sync tab visibility options
            if (options.enableEmojis !== undefined) instance.enableEmojis = options.enableEmojis !== false;
            if (options.enableStickers !== undefined) instance.enableStickers = options.enableStickers !== false;
            if (options.enableGifs !== undefined) instance.enableGifs = options.enableGifs !== false;
            instance.updateTabsVisibility();

            instance.toggle();
            return instance;
        }

        window.esgStudioInstance = this;

        this.triggerElement = typeof options.trigger === 'string'
            ? document.querySelector(options.trigger)
            : options.trigger;
        
        if (!this.triggerElement && window.event) {
            let target = window.event.target;
            while (target && target !== document.body) {
                if (target.tagName === 'BUTTON' || target.getAttribute('role') === 'button' || target.id === 'togglePickerBtn') {
                    this.triggerElement = target;
                    break;
                }
                target = target.parentElement;
            }
        }
        
        this.onSelect = options.onSelect || (() => { });
        this.theme = localStorage.getItem('esg-studio-theme') || options.theme || 'dark';

        this.emojis = typeof emojisData !== 'undefined' ? emojisData : [];
        this.stickers = typeof stickersData !== 'undefined' ? stickersData : [];
        this.gifs = typeof gifsData !== 'undefined' ? gifsData : [];

        this.enableEmojis = options.enableEmojis !== false;
        this.enableStickers = options.enableStickers !== false;
        this.enableGifs = options.enableGifs !== false;

        this.currentTab = 'emoji';
        this.hoverTimeout = null;
        this.justShown = false;

        this.init();

        if (!options.trigger) {
            this.show();
        }
    }

    init() {
        this.createMarkup();
        this.setupElements();
        this.setupEvents();
        this.updateTabsVisibility();
    }

    createMarkup() {
        // Create the wrapper/overlay backdrop element
        this.wrapperEl = document.createElement('div');
        this.wrapperEl.className = 'esg-studio-wrapper';
        this.wrapperEl.id = 'esgStudioWrapper';

        // Create picker HTML with BEM structure
        this.pickerEl = document.createElement('div');
        this.pickerEl.className = 'esg-studio';
        this.pickerEl.id = 'esgStudioPicker';
        if (this.theme) {
            this.pickerEl.setAttribute('data-theme', this.theme);
        }
        this.pickerEl.innerHTML = `
            <div class="esg-studio__header" id="topTabs"></div>
            <div class="esg-studio__search-bar" id="searchBar" style="display: none;">
                ${getIconSvg('bi-search', 16)}
                <input type="text" id="searchInput" placeholder="Search emojis...">
                <span class="esg-studio__close-search" id="closeSearch" style="display: inline-flex; align-items: center; justify-content: center;">${getIconSvg('bi-x-circle-fill', 16)}</span>
            </div>
            <div class="esg-studio__body" id="pickerBody"></div>
            <div class="esg-studio__footer">
                <div class="esg-studio__footer-left">
                    <button class="esg-studio__footer-btn search-trigger" title="Search">${getIconSvg('bi-search', 18)}</button>
                    <button class="esg-studio__footer-btn theme-toggle-btn" title="Toggle Theme">${getIconSvg(this.theme === 'light' ? 'bi-sun-fill' : 'bi-moon-fill', 18)}</button>
                </div>
                <div class="esg-studio__footer-tabs">
                    <button class="esg-studio__footer-btn esg-studio__footer-btn--active" data-tab="emoji" title="Emojis">${getIconSvg('bi-emoji-smile', 18)}</button>
                    <button class="esg-studio__footer-btn" data-tab="sticker" title="Stickers">${getIconSvg('bi-sticky', 18)}</button>
                    <button class="esg-studio__footer-btn esg-studio__footer-btn--gif" data-tab="gif" title="GIFs">GIF</button>
                </div>
                <div class="esg-studio__footer-right">
                    <button class="esg-studio__footer-btn close-picker-btn" title="Close">${getIconSvg('bi-chevron-down', 18)}</button>
                </div>
            </div>
        `;

        // Create large preview markup if it doesn't exist
        if (!document.getElementById('largePreview')) {
            this.largePreviewEl = document.createElement('div');
            this.largePreviewEl.className = 'esg-studio__large-preview';
            this.largePreviewEl.id = 'largePreview';
            this.largePreviewEl.innerHTML = `<img src="" id="largePreviewImg" alt="Sticker Preview">`;
            // Append preview inside overlay wrapper
            this.wrapperEl.appendChild(this.largePreviewEl);
        } else {
            this.largePreviewEl = document.getElementById('largePreview');
        }

        this.largePreviewImg = this.largePreviewEl.querySelector('#largePreviewImg');

        // Append picker inside overlay wrapper
        this.wrapperEl.appendChild(this.pickerEl);

        // Inject overlay wrapper into document body
        document.body.appendChild(this.wrapperEl);
    }

    setupElements() {
        this.topTabsContainer = this.pickerEl.querySelector('#topTabs');
        this.pickerBody = this.pickerEl.querySelector('#pickerBody');
        this.bottomBtns = this.pickerEl.querySelectorAll('.esg-studio__footer-tabs .esg-studio__footer-btn');
        this.searchBar = this.pickerEl.querySelector('#searchBar');
        this.searchInput = this.pickerEl.querySelector('#searchInput');
        this.closeSearchBtn = this.pickerEl.querySelector('#closeSearch');
        this.openSearchBtn = this.pickerEl.querySelector('.esg-studio__footer .esg-studio__footer-btn.search-trigger');
        this.closePickerBtn = this.pickerEl.querySelector('.esg-studio__footer .close-picker-btn');
        this.themeToggleBtn = this.pickerEl.querySelector('.esg-studio__footer .theme-toggle-btn');
        this.themeIcon = null;
    }

    showPreview(url) {
        clearTimeout(this.hoverTimeout);
        this.hoverTimeout = setTimeout(() => {
            this.largePreviewImg.src = url;
            this.largePreviewEl.classList.add('esg-studio__large-preview--show');
        }, 300);
    }

    hidePreview() {
        clearTimeout(this.hoverTimeout);
        this.largePreviewEl.classList.remove('esg-studio__large-preview--show');
    }

    render(query = '') {
        this.topTabsContainer.innerHTML = '';
        this.pickerBody.innerHTML = '';

        if (!this.currentTab) {
            this.searchInput.placeholder = "No tabs enabled";
            this.pickerBody.innerHTML = `
                <div class="esg-studio__empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--tg-text-muted); font-size: 14px; font-style: italic;">
                    <span style="display: inline-flex; margin-bottom: 8px; color: var(--tg-text-muted); opacity: 0.8;">
                        ${getIconSvg('bi-x-circle', 28)}
                    </span>
                    No tabs enabled
                </div>
            `;
            return;
        }

        if (this.currentTab === 'emoji') {
            this.searchInput.placeholder = "Search emojis...";

            if (!query) {
                this.emojis.forEach((cat, idx) => {
                    const tab = document.createElement('div');
                    tab.className = `esg-studio__top-tab ${idx === 0 ? 'esg-studio__top-tab--active' : ''}`;
                    tab.title = cat.title;
                    tab.innerHTML = getIconSvg(cat.icon, 18);
                    tab.onclick = () => this.scrollToCat(`cat-${cat.id}`, tab);
                    this.topTabsContainer.appendChild(tab);
                });
            }

            this.emojis.forEach(cat => {
                const filteredItems = cat.items.filter(emoji => {
                    if (!query) return true;

                    const lowerQuery = query.toLowerCase().trim();
                    const name = emoji.name.toLowerCase();

                    // Direct match
                    if (name.includes(lowerQuery) || emoji.char.includes(lowerQuery)) {
                        return true;
                    }

                    // Synonym mapping
                    for (const [key, synonyms] of Object.entries(emojiSynonyms)) {
                        if (lowerQuery.includes(key) || key.includes(lowerQuery)) {
                            if (synonyms.some(syn => name.includes(syn))) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

                if (filteredItems.length === 0) return;

                const title = document.createElement('div');
                title.className = 'esg-studio__category-title';
                title.id = `cat-${cat.id}`;
                title.textContent = cat.title;
                this.pickerBody.appendChild(title);

                const grid = document.createElement('div');
                grid.className = 'esg-studio__emoji-grid';

                filteredItems.forEach(emoji => {
                    const item = document.createElement('div');
                    item.className = 'esg-studio__emoji-item';
                    item.innerHTML = `
                        ${emoji.char}
                        <div class="esg-studio__emoji-tooltip">${emoji.name}</div>
                    `;
                    item.onclick = () => this.selectItem('emoji', emoji.char);
                    grid.appendChild(item);
                });
                this.pickerBody.appendChild(grid);
            });

        } else if (this.currentTab === 'sticker') {
            this.searchInput.placeholder = "Search stickers...";

            if (!query) {
                this.stickers.forEach((pack, idx) => {
                    const tab = document.createElement('div');
                    tab.className = `esg-studio__top-tab ${idx === 0 ? 'esg-studio__top-tab--active' : ''}`;
                    tab.title = pack.id.charAt(0).toUpperCase() + pack.id.slice(1);
                    tab.innerHTML = `<img src="${pack.icon}" alt="pack">`;
                    tab.onclick = () => this.scrollToCat(`pack-${pack.id}`, tab);
                    this.topTabsContainer.appendChild(tab);
                });
            }

            this.stickers.forEach(pack => {
                if (query && !pack.id.toLowerCase().includes(query.toLowerCase()) && !pack.title.toLowerCase().includes(query.toLowerCase())) return;

                const title = document.createElement('div');
                title.className = 'esg-studio__category-title';
                title.id = `pack-${pack.id}`;
                title.textContent = pack.title;
                this.pickerBody.appendChild(title);

                const grid = document.createElement('div');
                grid.className = 'esg-studio__sticker-grid';

                pack.items.forEach(url => {
                    const item = document.createElement('div');
                    item.className = 'esg-studio__sticker-item';
                    item.innerHTML = `
                        <img src="${url}" loading="lazy" alt="sticker" onerror="this.parentElement.style.display='none'">
                        <div class="esg-studio__sticker-tooltip">${pack.title} Sticker</div>
                    `;

                    item.addEventListener('mouseenter', () => this.showPreview(url));
                    item.addEventListener('mouseleave', () => this.hidePreview());
                    item.addEventListener('mousedown', () => this.hidePreview());
                    item.onclick = () => this.selectItem('sticker', url);

                    grid.appendChild(item);
                });
                this.pickerBody.appendChild(grid);
            });
        } else if (this.currentTab === 'gif') {
            this.searchInput.placeholder = "Search GIFs...";

            if (!query) {
                this.gifs.forEach((cat, idx) => {
                    const tab = document.createElement('div');
                    tab.className = `esg-studio__top-tab ${idx === 0 ? 'esg-studio__top-tab--active' : ''}`;
                    tab.title = cat.title;
                    tab.innerHTML = getIconSvg(cat.icon, 18);
                    tab.onclick = () => this.scrollToCat(`gif-${cat.id}`, tab);
                    this.topTabsContainer.appendChild(tab);
                });
            }

            this.gifs.forEach(cat => {
                if (query && !cat.id.toLowerCase().includes(query.toLowerCase()) && !cat.title.toLowerCase().includes(query.toLowerCase())) return;

                const anchor = document.createElement('div');
                anchor.id = `gif-${cat.id}`;
                anchor.style.height = '4px';
                this.pickerBody.appendChild(anchor);

                const grid = document.createElement('div');
                grid.className = 'esg-studio__sticker-grid';
                grid.style.gridTemplateColumns = 'repeat(2, 1fr)';

                cat.items.forEach(url => {
                    const item = document.createElement('div');
                    item.className = 'esg-studio__sticker-item';
                    item.style.padding = '0';
                    item.innerHTML = `
                        <img src="${url}" loading="lazy" alt="gif" style="border-radius: 6px; object-fit: cover;" onerror="this.parentElement.style.display='none'">
                        <div class="esg-studio__sticker-tooltip">${cat.title} GIF</div>
                    `;
                    item.onclick = () => this.selectItem('gif', url);
                    grid.appendChild(item);
                });
                this.pickerBody.appendChild(grid);
            });
        }
    }

    scrollToCat(id, tabElement) {
        const target = this.pickerBody.querySelector(`#${id}`);
        if (target) {
            this.pickerBody.scrollTo({
                top: target.offsetTop,
                behavior: 'smooth'
            });
            this.pickerEl.querySelectorAll('.esg-studio__top-tab').forEach(t => t.classList.remove('esg-studio__top-tab--active'));
            tabElement.classList.add('esg-studio__top-tab--active');
        }
    }

    selectItem(type, val) {
        this.onSelect(type, val);
    }

    setupEvents() {
        // Sync top tabs with scroll
        this.pickerBody.addEventListener('scroll', () => {
            if (this.searchBar.style.display === 'flex') return;

            const tabs = this.currentTab === 'emoji' ? this.emojis : (this.currentTab === 'sticker' ? this.stickers : this.gifs);
            const prefix = this.currentTab === 'emoji' ? 'cat-' : (this.currentTab === 'sticker' ? 'pack-' : 'gif-');

            let activeId = null;
            tabs.forEach(item => {
                const el = this.pickerBody.querySelector(`#${prefix}${item.id}`);
                if (el && el.offsetTop <= this.pickerBody.scrollTop + 20) {
                    activeId = item.id;
                }
            });

            if (activeId) {
                const tabElements = this.pickerEl.querySelectorAll('.esg-studio__top-tab');
                tabElements.forEach((t, idx) => {
                    t.classList.remove('esg-studio__top-tab--active');
                    if (tabs[idx].id === activeId) {
                        t.classList.add('esg-studio__top-tab--active');
                        t.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }
                });
            }
        });

        // Search events
        this.openSearchBtn.addEventListener('click', () => {
            this.topTabsContainer.style.display = 'none';
            this.searchBar.style.display = 'flex';
            this.searchInput.focus();
            this.render(this.searchInput.value);
        });

        this.closeSearchBtn.addEventListener('click', () => {
            this.searchInput.value = '';
            this.searchBar.style.display = 'none';
            this.topTabsContainer.style.display = 'flex';
            this.render();
        });

        this.searchInput.addEventListener('input', (e) => {
            this.render(e.target.value);
        });

        // Bottom footer switching
        this.bottomBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                this.bottomBtns.forEach(b => b.classList.remove('esg-studio__footer-btn--active'));
                btn.classList.add('esg-studio__footer-btn--active');
                this.currentTab = btn.getAttribute('data-tab');
                this.render(this.searchBar.style.display === 'flex' ? this.searchInput.value : '');
            });
        });



        // Close picker button click
        if (this.closePickerBtn) {
            this.closePickerBtn.addEventListener('click', () => {
                this.hide();
            });
        }

        // Theme toggle button click
        if (this.themeToggleBtn) {
            this.themeToggleBtn.addEventListener('click', () => {
                const newTheme = this.theme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            });
        }

        // Trigger button logic
        this.setupTriggerEvent();

        // Close on clicking directly on the overlay backdrop
        this.wrapperEl.addEventListener('click', (e) => {
            if (e.target === this.wrapperEl) {
                this.hide();
            }
        });

        // Close on clicking outside
        document.addEventListener('click', (e) => {
            if (this.justShown) return;
            if (this.wrapperEl.classList.contains('esg-studio-wrapper--show') &&
                !this.pickerEl.contains(e.target) &&
                !this.largePreviewEl.contains(e.target) &&
                e.target !== this.triggerElement &&
                (!this.triggerElement || !this.triggerElement.contains(e.target))) {
                this.hide();
            }
        });
    }

    setupTriggerEvent() {
        if (this.triggerElement) {
            if (this.triggerHandler) {
                this.triggerElement.removeEventListener('click', this.triggerHandler);
            }
            this.triggerHandler = (e) => {
                e.stopPropagation();
                this.toggle();
            };
            this.triggerElement.addEventListener('click', this.triggerHandler);
        }
    }

    toggle() {
        if (this.toggledThisTick) return;
        this.toggledThisTick = true;
        setTimeout(() => {
            this.toggledThisTick = false;
        }, 0);

        if (this.pickerEl.classList.contains('esg-studio--show')) {
            this.hide();
        } else {
            this.show();
        }
    }

    show() {
        this.wrapperEl.classList.add('esg-studio-wrapper--show');
        this.justShown = true;
        setTimeout(() => {
            this.justShown = false;
        }, 0);
    }

    hide() {
        this.wrapperEl.classList.remove('esg-studio-wrapper--show');
    }

    setTheme(theme) {
        this.theme = theme;
        if (this.pickerEl) {
            this.pickerEl.setAttribute('data-theme', theme);
        }
        if (this.themeToggleBtn) {
            this.themeToggleBtn.innerHTML = getIconSvg(theme === 'light' ? 'bi-sun-fill' : 'bi-moon-fill', 18);
        }
        localStorage.setItem('esg-studio-theme', theme);
    }

    updateTabsVisibility() {
        const emojiBtn = this.pickerEl.querySelector('.esg-studio__footer-tabs button[data-tab="emoji"]');
        const stickerBtn = this.pickerEl.querySelector('.esg-studio__footer-tabs button[data-tab="sticker"]');
        const gifBtn = this.pickerEl.querySelector('.esg-studio__footer-tabs button[data-tab="gif"]');

        if (emojiBtn) emojiBtn.style.display = this.enableEmojis ? 'flex' : 'none';
        if (stickerBtn) stickerBtn.style.display = this.enableStickers ? 'flex' : 'none';
        if (gifBtn) gifBtn.style.display = this.enableGifs ? 'flex' : 'none';

        // Resolve active tab
        if (this.currentTab === 'emoji' && !this.enableEmojis) {
            this.currentTab = this.enableStickers ? 'sticker' : (this.enableGifs ? 'gif' : '');
        } else if (this.currentTab === 'sticker' && !this.enableStickers) {
            this.currentTab = this.enableEmojis ? 'emoji' : (this.enableGifs ? 'gif' : '');
        } else if (this.currentTab === 'gif' && !this.enableGifs) {
            this.currentTab = this.enableEmojis ? 'emoji' : (this.enableStickers ? 'sticker' : '');
        } else if (!this.currentTab) {
            this.currentTab = this.enableEmojis ? 'emoji' : (this.enableStickers ? 'sticker' : (this.enableGifs ? 'gif' : ''));
        }

        // Sync footer active states
        this.bottomBtns.forEach(btn => {
            const tab = btn.getAttribute('data-tab');
            if (tab === this.currentTab) {
                btn.classList.add('esg-studio__footer-btn--active');
            } else {
                btn.classList.remove('esg-studio__footer-btn--active');
            }
        });

        this.render();
    }

    setEmojisEnabled(enabled) {
        this.enableEmojis = !!enabled;
        this.updateTabsVisibility();
    }

    enableEmojis(enabled) {
        this.setEmojisEnabled(enabled);
    }

    setStickersEnabled(enabled) {
        this.enableStickers = !!enabled;
        this.updateTabsVisibility();
    }

    enableStickers(enabled) {
        this.setStickersEnabled(enabled);
    }

    setGifsEnabled(enabled) {
        this.enableGifs = !!enabled;
        this.updateTabsVisibility();
    }

    enableGifs(enabled) {
        this.setGifsEnabled(enabled);
    }
}
window.ESGStudio = ESGStudio;
})();

