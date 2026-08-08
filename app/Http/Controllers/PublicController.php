<?php

namespace App\Http\Controllers;

use App\Actions\Public\CheckPpdbStatusAction;
use App\Actions\Public\GetCalendarEventsAction;
use App\Actions\Public\GetLandingPageDataAction;
use App\Actions\Public\GetNewsDetailAction;
use App\Actions\Public\GetNewsListAction;
use App\Actions\Public\GetProfileDataAction;
use App\Actions\Public\ProcessPpdbRegistrationAction;
use App\Actions\Public\StoreContactMessageAction;
use App\Http\Requests\Public\StoreContactMessageRequest;
use App\Http\Requests\Public\StorePpdbRegistrationRequest;

class PublicController extends Controller
{
    public function home(GetLandingPageDataAction $action)
    {
        $data = $action->execute();

        return view('landing', $data);
    }

    public function profil(GetProfileDataAction $action)
    {
        $data = $action->execute();

        return view('public.profile', $data);
    }

    public function berita(GetNewsListAction $action)
    {
        $posts = $action->execute();

        return view('public.news.news', compact('posts'));
    }

    public function showBerita(string $slug, GetNewsDetailAction $action)
    {
        $data = $action->execute($slug);

        return view('public.news.news-show', $data);
    }

    public function kontak()
    {
        return view('public.contact');
    }

    public function jadwal()
    {
        return view('public.timetable');
    }

    public function kalender(GetCalendarEventsAction $action)
    {
        $eventsByMonth = $action->execute();

        return view('public.calender', compact('eventsByMonth'));
    }

    public function storeContact(
        StoreContactMessageRequest $request,
        StoreContactMessageAction $action,
    ) {
        $action->execute($request->validated());

        return back()->with(
            'success',
            'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.',
        );
    }

    /**
     * Tampilkan halaman pembuka PPDB.
     */
    public function ppdb(CheckPpdbStatusAction $action)
    {
        if (! $action->execute()) {
            return view('public.ppdb-closed');
        }

        return view('public.ppdb');
    }

    /**
     * Tampilkan formulir pendaftaran PPDB.
     */
    public function ppdbForm(CheckPpdbStatusAction $action)
    {
        if (! $action->execute()) {
            return view('public.ppdb-closed');
        }

        return view('public.ppdb-form');
    }

    /**
     * Simpan data pendaftar PPDB.
     */
    public function storePpdb(
        StorePpdbRegistrationRequest $request,
        CheckPpdbStatusAction $checkPpdbStatusAction,
        ProcessPpdbRegistrationAction $action,
    ) {
        if (! $checkPpdbStatusAction->execute()) {
            return back()->with(
                'error',
                'Pendaftaran PPDB saat ini sedang ditutup.',
            );
        }

        $noReg = $action->execute($request->validated());

        return back()->with(
            'success',
            "Pendaftaran berhasil! Nomor Registrasi Anda: {$noReg}. Simpan nomor ini untuk keperluan verifikasi.",
        );
    }
}
