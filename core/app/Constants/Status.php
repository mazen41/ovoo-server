<?php

namespace App\Constants;

class Status
{

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;

    const TICKET_OPEN = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY = 2;
    const TICKET_CLOSE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING = 2;
    const KYC_VERIFIED = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM = 3;

    const TEMPLATE_PENDING  = 0;
    const TEMPLATE_APPROVED = 1;
    const TEMPLATE_REJECTED = 2;
    const TEMPLATE_DISABLED = 3;

    const MONTHLY_PLAN = 1;
    const YEARLY_PLAN = 2;

    const MONTHLY = 1;
    const YEARLY = 2;

    const WALLET_PAYMENT  = 1;
    const GATEWAY_PAYMENT = 2;

    const INVOICE_PAID = 1;
    const INVOICE_UNPAID = 0;

    const SUBSCRIBED_CONTACT = 1;
    const UNSUBSCRIBED_CONTACT = 0;


    const DONE_CONVERSATION = 1;
    const PENDING_CONVERSATION = 2;
    const IMPORTANT_CONVERSATION = 3;
    const UNREAD_CONVERSATION = 4;

    const MESSAGE_SENT = 1;
    const MESSAGE_RECEIVED = 2;

    const TEXT_RESPONSE = 1;
    const TEMPLATE_RESPONSE = 2;

    const CAMPAIGN_COMPLETED = 1;
    const CAMPAIGN_RUNNING = 2;
    const CAMPAIGN_SCHEDULED = 3;

    const TEXT_TYPE_MESSAGE = 1;
    const IMAGE_TYPE_MESSAGE = 2;
    const VIDEO_TYPE_MESSAGE = 3;
    const DOCUMENT_TYPE_MESSAGE = 4;
    const AUDIO_TYPE_MESSAGE = 5;
    const URL_TYPE_MESSAGE = 6;
    const BUTTON_TYPE_MESSAGE = 7;
    const LOCATION_TYPE_MESSAGE = 8;
    const LIST_TYPE_MESSAGE = 9;
    const REPLY_TYPE_MESSAGE = 0;

    const SENT = 1;
    const DELIVERED = 2;
    const READ = 3;
    const FAILED = 9;
    const SCHEDULED = 0;

    const SUPPER_ADMIN_ID     = 1;
    const SUPER_ADMIN_ROLE_ID = 1;

    const COUPON_TYPE_PERCENTAGE = 1;
    const COUPON_TYPE_FIXED = 2;

    const UNLIMITED = -1;

    const COUPON_EXPIRED = 2;
    const COUPON_ACTIVE = 1;
    const COUPON_INACTIVE = 0;


    const FLOW_TRIGGER_NEW_MESSAGE = 1;
    const FLOW_TRIGGER_KEYWORD_MATCH = 2;

    const NODE_TYPE_TEXT     = 1;
    const NODE_TYPE_IMAGE    = 2;
    const NODE_TYPE_VIDEO    = 3;
    const NODE_TYPE_AUDIO    = 4;
    const NODE_TYPE_DOCUMENT = 5;
    const NODE_TYPE_CTA_URL  = 6;
    const NODE_TYPE_LOCATION = 7;
    const NODE_TYPE_LIST     = 8;
    const NODE_TYPE_BUTTON   = 9;
    const NODE_TYPE_TEMPLATE = 10;

    const NODE_MEDIA_IMAGE    = 1;
    const NODE_MEDIA_VIDEO    = 2;
    const NODE_MEDIA_AUDIO    = 3;
    const NODE_MEDIA_DOCUMENT = 4;

    const FLOW_STATE_WAITING = 0;
    const FLOW_STATE_SENT = 1;

    // E-commerce Status
    const WOO_COMMERCE = 1;
    const SHOPIFY      = 2;
}
