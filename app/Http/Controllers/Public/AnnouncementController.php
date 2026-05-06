<?php

namespace App\Http\Controllers\Public;

use App\Models\Announcement;
use App\Services\Public\ViewCounterService;

class AnnouncementController extends BasePublicController
{
    public function index()
    {
        return $this->listPage(Announcement::class, 'announcements', 'Pengumuman', 'Informasi penting dari pemerintah desa.', 'public.announcements.show');
    }

    public function show(Announcement $announcement, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($announcement);
        $counter->increment($announcement);

        return $this->detailPage($announcement, $announcement->title, 'public.announcements.index', $announcement->content, [
            'Dilihat' => number_format((int) $announcement->views + 1, 0, ',', '.'),
        ]);
    }
}
