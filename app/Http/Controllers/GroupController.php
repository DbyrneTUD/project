<?php

namespace App\Http\Controllers;

use App\Models\Group;

class GroupController extends Controller
{
    //
    public function index()
    {
        $q = request('q');

        $groups = Group::latest();

        if ($q) {
            $groups->where('name', 'like', '%'.$q.'%')->orWhere('description', 'like', '%'.$q.'%');
        }

        $groups = $groups->paginate(5)->withQueryString();

        return view('groups.index', [
            'groups' => $groups,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', 'min:3'],
            'description' => ['nullable'],
            'photo' => ['nullable', 'image'],
        ]);

        $photoPath = null;

        if (request()->hasFile('photo')) {
            $photoPath = request()->file('photo')->store('photos', 'public');
        }

        $group = Group::create([
            'name' => request('name'),
            'description' => request('description'),
            'created_by' => auth()->id(),
            'photo_path' => $photoPath,
        ]);

        $group->members()->attach(auth()->id());

        return redirect('/groups');
    }

    public function join(Group $group)
    {
        $group->members()->attach(auth()->id());

        return redirect("/groups/{$group->id}");
    }

    public function leave(Group $group)
    {
        $group->members()->detach(auth()->id());

        return redirect('/groups.index');
    }

    public function show(Group $group)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        $status = request('status') ? request('status') : 'all';

        $requests = $group->liftRequests()->latest();
        if ($status !== 'all') {
            $requests->where('status', $status);
        }

        $requests = $requests->paginate(6);

        return view('groups.show', [
            'group' => $group,
            'requests' => $requests,
            'status' => $status,
        ]);
    }

    public function edit(Group $group)
    {
        return view('groups.edit', [
            'group' => $group,
        ]);
    }

    public function update(Group $group)
    {
        if ($group->created_by !== auth()->id()) {
            return redirect('/groups');
        }

        request()->validate([
            'name' => ['required', 'min:3'],
            'description' => ['nullable'],
            'photo' => ['nullable', 'image'],
        ]);

        $photoPath = $group->photo_path;

        if (request()->hasFile('photo')) {
            $photoPath = request()->file('photo')->store('photos', 'public');
        }

        $group->update([
            'name' => request('name'),
            'description' => request('description'),
            'photo_path' => $photoPath,
        ]);

        return redirect("/groups/{$group->id}");

    }

    public function destroy(Group $group)
    {
        if ($group->created_by !== auth()->id()) {
            return redirect('/groups');
        }
        $group->delete();

        return redirect('/groups');
    }
}
