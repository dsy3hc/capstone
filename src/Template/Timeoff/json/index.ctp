<?php
    $events = [];
    foreach ($requests as $request) {
        $options = $request->getOptions($request->time_selected);
        foreach ($options as $option) {
            $event = [
                'start' => $option['start'],
                'end' => $option['end'],
                'title' => $request->full_name,
                'id' => $request->id,
                'comments' => $request->comments,
                'option' => $option['id'],
                'pending' => ($request->status == 0),
                'viewing' => $viewing
            ];

            array_push($events, $event);
        }
    }

    echo json_encode($events);