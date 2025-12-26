<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Event;
use App\Models\Message;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $latest_posts = Post::where('is_published', true)->latest()->take(3)->get();
        return view('landing', compact('latest_posts'));
    }

    public function profil()
    {
        return view('public.profile');
    }

    public function berita()
    {
        $posts = Post::where('is_published', true)->latest()->paginate(9);
        return view('public.news.news', compact('posts'));
    }

    public function showBerita($slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $recent_posts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('public.news.news-show', compact('post', 'recent_posts'));
    }

    public function kontak()
    {
        return view('public.contact');
    }

    public function jadwal()
    {
        return view('public.timetable');
    }

    public function kalender()
    {
        $events = Event::orderBy('start_date', 'asc')->get();

        $eventsByMonth = $events->groupBy(function ($date) {
            return Carbon::parse($date->start_date)->isoFormat('MMMM Y');
        });

        return view('public.calender', compact('eventsByMonth'));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($request->all());

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}
