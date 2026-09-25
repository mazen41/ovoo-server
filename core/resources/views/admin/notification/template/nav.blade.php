<div class="col-lg-4">
    <div class="row gy-md-4 gy-2">
        <div class="col-12">
            <a href="{{ route('admin.setting.notification.template.edit',['email',$template->id]) }}"
                class="w-100 {{ menuActive('admin.setting.notification.template.edit',param:'email') }}">
                <div class="template-card">
                    <span class="template-card__icon">
                        <i class="las la-envelope-open"></i>
                    </span>
                    <h4 class="template-card__title">
                        @lang('Email Template')
                    </h4>
                </div>
            </a>
        </div>

        <div class="col-12">
            <a href="{{ route('admin.setting.notification.template.edit',['sms',$template->id]) }}"
                class="w-100  {{ menuActive('admin.setting.notification.template.edit',param:'sms') }}">
                <div class="template-card">
                    <span class="template-card__icon">
                        <i class="la la-sms"></i>
                    </span>
                    <h4 class="template-card__title">
                        @lang('SMS Template')
                    </h4>

                </div>
            </a>
        </div>
        <div class="col-12">
            <a href="{{ route('admin.setting.notification.template.edit',['push',$template->id]) }}"
                class="w-100  {{ menuActive('admin.setting.notification.template.edit',param:'push') }}">
                <div class="template-card">
                    <span class="template-card__icon">
                        <i class="las la-bell"></i>
                    </span>
                    <h4 class="template-card__title">
                        @lang('Push Notification Template')
                    </h4>
                </div>
            </a>
        </div>
    </div>
</div>

@push('style')
    <style>

        .template-card {
            display: flex;
            align-items: center;
            padding: 17px 25px;
            background-color: hsl(var(--secondary)/0.06);
            border-radius: 4px;
        }

        .template-card__icon {
            --size: 48px;
            width: var(--size);
            height: var(--size);
            border-radius: 4px;
            background-color: hsl(var(--secondary)/0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: hsl(var(--secondary));
            font-size: 18px;
            margin-right: 16px;

        }

        .template-card__title {
            margin-bottom: 0;
        }

        .active .template-card__title {
            color: hsl(var(--primary));
        }

        .active .template-card__icon {
            color: hsl(var(--white));
            background-color: hsl(var(--primary));
        }
    </style>
@endpush
