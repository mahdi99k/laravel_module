<?php

namespace Webamooz\Slider\Database\Repositories;

use Webamooz\Slider\Models\Slide;

class SlideRepositories
{

    public function paginate($count = 10)
    {
        return Slide::query()->orderBy('priority', 'asc')->paginate($count);
    }

    public function all()
    {
        return Slide::query()->where('status', true)->orderBy('priority', 'asc')->get();
    }

    public function findById($slide_id)
    {
        return Slide::query()->findOrFail($slide_id);
    }

    public function store($values, $media_id)
    {
        return Slide::create([
            'user_id' => auth()->id(),
            'media_id' => $media_id,
            'priority' => $values->priority,
            'link' => $values->link,
            'status' => $values->status,
        ]);
    }

    public function update($values, $slide_id, $media_id)
    {
        return Slide::where('id', $slide_id)->update([
            'media_id' => $media_id,
            'priority' => $values->priority,
            'link' => $values->link,
            'status' => $values->status,
        ]);
    }

}
