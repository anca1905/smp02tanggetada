<?php

namespace Tests\Unit;

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_subject_returns_appropriate_theme_by_name(): void
    {
        $math = new Subject(['name' => 'Matematika']);
        $this->assertSame('fas fa-calculator', $math->theme['icon']);
        $this->assertStringContainsString('blue', $math->theme['gradient']);

        $indo = new Subject(['name' => 'Bahasa Indonesia']);
        $this->assertSame('fas fa-book-open', $indo->theme['icon']);
        $this->assertStringContainsString('emerald', $indo->theme['gradient']);

        $ipa = new Subject(['name' => 'Ilmu Pengetahuan Alam (IPA)']);
        $this->assertSame('fas fa-flask', $ipa->theme['icon']);

        $pjok = new Subject(['name' => 'Pendidikan Jasmani dan Olahraga']);
        $this->assertSame('fas fa-running', $pjok->theme['icon']);

        $general = new Subject(['name' => 'Muatan Lokal']);
        $this->assertSame('fas fa-graduation-cap', $general->theme['icon']);
    }
}
