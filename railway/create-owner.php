<?php
// Railway first-boot bootstrap: create the Shoutrrr instance owner with SHOUTRRR_ADMIN_PASSWORD, mirroring
// upstream's own registration flow (CreateNewUser: create user, claim owner, provision default workspace).
require '/var/www/html/vendor/autoload.php';
$app = require '/var/www/html/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Services\Workspace\WorkspaceProvisioningService;
use App\Support\InstanceSettings;
use Illuminate\Support\Facades\DB;

$settings = $app->make(InstanceSettings::class);
if ($settings->ownerExists()) {
    echo "[railway] instance owner already exists; nothing to do\n";
    exit(0);
}
$password = getenv('SHOUTRRR_ADMIN_PASSWORD') ?: '';
if (strlen($password) < 8) {
    fwrite(STDERR, "[railway] SHOUTRRR_ADMIN_PASSWORD is missing or shorter than 8 characters; owner not created\n");
    exit(1);
}
$email = strtolower(trim(getenv('SHOUTRRR_ADMIN_EMAIL') ?: 'admin@example.com'));
$name = getenv('SHOUTRRR_ADMIN_NAME') ?: 'Admin';

DB::transaction(function () use ($app, $settings, $email, $name, $password) {
    $user = User::query()->where('email', $email)->first() ?? User::create([
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ]);
    $user->forceFill(['email_verified_at' => now()])->save();
    $settings->claimOwnerIfMissing($user);
    if ($user->workspaceMemberships()->doesntExist()) {
        $app->make(WorkspaceProvisioningService::class)->provisionForNewUser($user, null);
    }
});
echo "[railway] created instance owner {$email} with the password from SHOUTRRR_ADMIN_PASSWORD (first boot)\n";
