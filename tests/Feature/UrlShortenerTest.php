<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, ?Company $company = null): User
    {
        return User::factory()->create([
            'company_id' => $company?->id,
            'role' => $role,
        ]);
    }

    public function test_super_admin_can_create_company_and_invite_admin(): void
    {
        $superAdmin = $this->user('super_admin');

        $this->actingAs($superAdmin)
            ->post(route('superadmin.companies.store'), ['name' => 'Acme'])
            ->assertRedirect();

        $company = Company::where('name', 'Acme')->firstOrFail();

        $this->actingAs($superAdmin)
            ->post(route('superadmin.invitations.store'), [
                'company_id' => $company->id,
                'email' => 'admin@example.com',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('invitations', [
            'company_id' => $company->id,
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_invitation_can_be_accepted(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $superAdmin = $this->user('super_admin');
        $invitation = Invitation::create([
            'company_id' => $company->id,
            'invited_by' => $superAdmin->id,
            'email' => 'newadmin@example.com',
            'role' => 'admin',
            'token' => Str::random(64),
            'expires_at' => now()->addDay(),
        ]);

        $this->post(route('invitations.accept', $invitation->token), [
            'name' => 'New Admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('login'));

        $user = User::where('email', 'newadmin@example.com')->firstOrFail();
        $this->assertSame($company->id, $user->company_id);
        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotNull($invitation->fresh()->accepted_at);
    }

    public function test_admin_can_only_invite_admin_or_member_into_own_company(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $admin = $this->user('admin', $company);

        $this->actingAs($admin)->post(route('admin.invitations.store'), [
            'email' => 'member@example.com',
            'role' => 'member',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('invitations', [
            'company_id' => $company->id,
            'email' => 'member@example.com',
            'role' => 'member',
        ]);

        $this->actingAs($admin)->post(route('admin.invitations.store'), [
            'email' => 'bad@example.com',
            'role' => 'super_admin',
        ])->assertSessionHasErrors('role');
    }

    public function test_admin_and_member_can_create_short_urls_but_super_admin_cannot(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $admin = $this->user('admin', $company);
        $member = $this->user('member', $company);
        $superAdmin = $this->user('super_admin');

        $this->actingAs($admin)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/admin',
        ])->assertSessionHasNoErrors();

        $this->actingAs($member)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/member',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('short_urls', 2);

        $this->actingAs($superAdmin)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/super',
        ])->assertForbidden();
    }

    public function test_short_url_visibility_follows_role_rules(): void
    {
        $companyA = Company::create(['name' => 'Company A']);
        $companyB = Company::create(['name' => 'Company B']);
        $adminA = $this->user('admin', $companyA);
        $memberA1 = $this->user('member', $companyA);
        $memberA2 = $this->user('member', $companyA);
        $memberB = $this->user('member', $companyB);
        $superAdmin = $this->user('super_admin');

        $urlA1 = ShortUrl::create(['company_id' => $companyA->id, 'user_id' => $memberA1->id, 'original_url' => 'https://example.com/a1', 'short_code' => 'codea1']);
        $urlA2 = ShortUrl::create(['company_id' => $companyA->id, 'user_id' => $memberA2->id, 'original_url' => 'https://example.com/a2', 'short_code' => 'codea2']);
        $urlB = ShortUrl::create(['company_id' => $companyB->id, 'user_id' => $memberB->id, 'original_url' => 'https://example.com/b', 'short_code' => 'codeb']);

        $this->actingAs($superAdmin)->get(route('short-urls.index'))
            ->assertSee($urlA1->original_url)->assertSee($urlA2->original_url)->assertSee($urlB->original_url);

        $this->actingAs($adminA)->get(route('short-urls.index'))
            ->assertSee($urlA1->original_url)->assertSee($urlA2->original_url)->assertDontSee($urlB->original_url);

        $this->actingAs($memberA1)->get(route('short-urls.index'))
            ->assertSee($urlA1->original_url)->assertDontSee($urlA2->original_url)->assertDontSee($urlB->original_url);
    }

    public function test_public_short_url_redirects_to_original_url(): void
    {
        $company = Company::create(['name' => 'Acme']);
        $member = $this->user('member', $company);
        $shortUrl = ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $member->id,
            'original_url' => 'https://example.com/page',
            'short_code' => 'abc1234',
        ]);

        $this->get(route('short-urls.redirect', $shortUrl->short_code))
            ->assertRedirect('https://example.com/page');
    }
}
