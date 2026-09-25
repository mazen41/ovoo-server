<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InteractiveList;
use Illuminate\Http\Request;

class InteractiveListController extends Controller
{
    public function index()
    {
        $pageTitle        = 'Interactive List';
        $interactiveLists = InteractiveList::where('user_id', getParentUser()?->id)->orderBy('id', 'desc')->searchable(['name'])->paginate(getPaginate());
        
        return view('Template::user.interactive_list.index', compact('pageTitle', 'interactiveLists'));
    }

    public function getList()
    {
        $user = getParentUser();
        $lists = InteractiveList::where('user_id', $user->id)->get();
        return apiResponse("interactive_lists", "success", ["Interactive List List"], [
            'lists' => $lists
        ]);
    }

    public function create()
    {
        $pageTitle = "Create Interactive List";
        return view('Template::user.interactive_list.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:20',
            'header'      => 'nullable|string|max:60',
            'body'        => 'required|string|max:4096',
            'footer'      => 'nullable|string|max:60',
            'button_text' => 'required|string|max:20',

            'sections'                         => 'required|array|min:1|max:10',
            'sections.*.title'                 => 'required|string|max:24',
            'sections.*.rows'                  => 'required|array|min:1',
            'sections.*.rows.*.title'          => 'required|string|max:24',
            'sections.*.rows.*.description'    => 'nullable|string|max:72',
        ],[
            'sections.max'                     => 'List message can have maximum 10 sections.',
            'sections.*.title.required'        => 'The section title field is required.',
            'sections.*.rows.*.title.required' => 'The row title field is required.',
            'sections.*.title.max'             => 'Section title can have maximum 24 characters.',
            'sections.*.description.max'       => 'Section description can have maximum 72 characters.',
        ]);

        $totalRows = collect($request->sections)->flatMap(fn($section) => $section['rows'] ?? [])->count();

        if($totalRows > 10){
            $notify[] = ['error', 'List message can have maximum 10 rows.'];
            return back()->withNotify($notify);
        }

        $user         = getParentUser();
        $interactiveList = InteractiveList::where('user_id', $user->id)->where('name', $request->name)->exists();

        if ($interactiveList) {
            $notify[] = ['error', 'This list name already exists.'];
            return back()->withNotify($notify);
        }

        if (!featureAccessLimitCheck($user->interactive_message)) {
            $notify[] = ['error', 'Your current plan does not support interactive messages. Please upgrade your plan.'];
            return back()->withNotify($notify);
        }

        $header = [];
        $footer = [];
        $sections = [];

        $body = [
            'text' => $request->body
        ];

        if($request->header != null){
            $header = [
                'type' => 'text',
                'text' => $request->header
            ];
        }

        if($request->footer != null){
            $footer = [
                'text' => $request->footer
            ];
        }

        foreach ($request->sections as $section) {
            $rows = [];
            foreach ($section['rows'] as $row) {
                $rows[] = [
                    'id'          => titleToKey($row['title']),
                    'title'       => $row['title'],
                    'description' => $row['description'],
                ];
            }
            $sections[] = [
                'title' => $section['title'],
                'rows'  => $rows,
            ];
        }

        $list              = new InteractiveList();
        $list->name        = $request->name;
        $list->user_id     = $user->id;
        $list->header      = $header;
        $list->body        = $body;
        $list->footer      = $footer;
        $list->sections    = $sections;
        $list->button_text = $request->button_text;
        $list->save();

        $notify[] = ['success', 'Interactive List created successfully'];
        return to_route('user.interactive-list.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $user   = getParentUser();

        $interactiveList = InteractiveList::where('user_id', $user->id)->find($id);

        if (!$interactiveList) {
            $notify[] = ['error', 'Interactive List not found'];
            return back()->withNotify($notify);
        }

        if ($interactiveList->messages()->count() > 0) {
            $notify[] = ['error', 'You can not delete this Interactive List. It has some messages'];
            return back()->withNotify($notify);
        }

        $interactiveList->delete();

        $notify[] = ['success', 'Interactive List deleted successfully'];
        return back()->withNotify($notify);
    }
}