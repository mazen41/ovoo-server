<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CtaUrl;
use Illuminate\Http\Request;

class CTAUrlController extends Controller
{
    public function index()
    {
        $pageTitle = 'CTA URL';
        $ctaUrls   = CtaUrl::where('user_id', getParentUser()?->id)->orderBy('id', 'desc')->searchable(['name'])->paginate(getPaginate());
        return view('Template::user.cta-url.index', compact('pageTitle', 'ctaUrls'));
    }

    public function getList()
    {
        $user = getParentUser();
        $ctaUrls = CtaUrl::where('user_id', $user->id)->orderBy('id', 'desc')->get(['id', 'name', 'cta_url']);
        return apiResponse("cta_urls", "success", ["CTA URL List"], [
            'ctaUrls' => $ctaUrls
        ]);
    }

    public function create()
    {
        $pageTitle = "Create CTA URL";
        return view('Template::user.cta-url.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cta_url_name' => 'required|string|max:20',
            'cta_url'      => 'required|url',
            'header_format' => 'required|in:TEXT,IMAGE',
            'message_body' => 'required|string|max:1024',
            'button_text'  => 'required|string|max:20',
            'footer'       => 'nullable|string|max:60',
            'header.text'  => 'required_if:header_format,TEXT|string|max:60',
            'header.image' => 'required_if:header_format,IMAGE|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user         = getParentUser();
        $ctaUrlExists = CtaUrl::where('user_id', $user->id)->where('name', $request->cta_url_name)->exists();

        if ($ctaUrlExists) {
            $notify[] = ['error', 'This CTA URL name already exists. Please enter a different name.'];
            return back()->withNotify($notify);
        }

        if (!featureAccessLimitCheck($user->interactive_message)) {
            $notify[] = ['error', 'Your current plan does not support interactive messages. Please upgrade your plan.'];
            return back()->withNotify($notify);
        }

        $header = [];

        $body = [
            'text' => $request->message_body
        ];

        $action = [
            'name'         => 'cta_url',
            'parameters'   => [
                'display_text' => $request->button_text,
                'url'          => $request->cta_url
            ]
        ];

        $footer = [];

        if ($request->footer != null) {
            $footer = [
                'text' => $request->footer
            ];
        }

        if ($request->header_format == 'IMAGE' && $request->header['image']) {
            $headerMediaName = fileUploader($request->header['image'], getFilePath('ctaHeader'), getFileSize('ctaHeader'));

            $header = [
                'type'  => 'image',
                'image' => [
                    'link' => getImage(getFilePath('ctaHeader') . '/' . $headerMediaName),
                ]
            ];
        } else {
            $header = [
                'type' => 'text',
                'text' => $request->header['text'],
            ];
        }

        $ctaUrl                 = new CtaUrl();
        $ctaUrl->user_id        = $user->id;
        $ctaUrl->name           = $request->cta_url_name;
        $ctaUrl->cta_url        = $request->cta_url;
        $ctaUrl->header_format  = $request->header_format;
        $ctaUrl->header         = $header;
        $ctaUrl->body           = $body;
        $ctaUrl->action         = $action;
        $ctaUrl->footer         = $footer;
        $ctaUrl->save();

        $notify[] = ['success', 'CTA URL created successfully'];
        return to_route('user.cta-url.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $user   = getParentUser();

        $ctaUrl = CtaUrl::where('user_id', $user->id)->find($id);

        if (!$ctaUrl) {
            $notify[] = ['error', 'CTA URL not found'];
            return back()->withNotify($notify);
        }

        if ($ctaUrl->messages()->count() > 0) {
            $notify[] = ['error', 'You can not delete this CTA URL. It has some messages'];
            return back()->withNotify($notify);
        }

        if ($ctaUrl->header_format == 'IMAGE') {
            $imageUrl  = $ctaUrl->header['image']['link'];
            $relativePath  = ltrim(str_replace(url('/'), '', $imageUrl), '/');

            if (file_exists($relativePath)) {
                unlink($relativePath);
            }
        }

        $ctaUrl->delete();

        $notify[] = ['success', 'CTA URL deleted successfully'];
        return back()->withNotify($notify);
    }
}
