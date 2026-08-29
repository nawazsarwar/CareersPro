<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Authentication strings
|--------------------------------------------------------------------------
|
| Retry times are stated, never "try again later" (M03 §5). A user who is told
| when they may retry can act; a user told to come back later reloads the page
| until they are throttled again.
|
*/

return [

    // Framework
    'failed' => 'Those credentials do not match our records.',
    'password' => 'The password is incorrect.',
    'throttle' => 'Too many attempts. Try again in :seconds seconds.',

    // Sign-in
    'sign_in' => 'Sign in',
    'sign_out' => 'Sign out',
    'sign_in_hint' => 'Use your email address, or your employee ID if you are a member of staff.',
    'login_label' => 'Email address or employee ID',
    'login_example' => 'For example aisha.khan@gmail.com, or EMP04821',
    'login_required' => 'Enter your email address or employee ID.',
    'password_field' => 'Password',
    'remember_me' => 'Keep me signed in',
    'forgot_password' => 'Forgotten your password?',
    'create_account' => 'Create an account',
    'have_account' => 'Already have an account? Sign in',
    'send_code_instead' => 'Send me a code instead',
    'use_password_instead' => 'Sign in with your password instead',

    'status_suspended' => 'This account is suspended. Contact the Registrar’s Office.',
    'status_locked' => 'This account is locked. Reset your password to unlock it.',

    // Registration
    'full_name' => 'Full name',
    'email' => 'Email address',
    'confirm_password' => 'Confirm password',
    'new_password' => 'New password',
    'password_policy' => 'Use at least 12 characters with upper and lower case, a number and a symbol.',
    'email_taken' => 'That email is already registered. Sign in instead.',
    'consent' => 'I have read the privacy notice (version :version) and agree to my data being processed for recruitment.',
    'consent_required' => 'You must accept the privacy notice to create an account.',

    // Email verification
    'verify_email' => 'Verify your email address',
    'verify_email_hint' => 'We have sent you a link. Open it to finish setting up your account.',
    'resend_verification' => 'Send the link again',
    'verification_sent' => 'A new verification link is on its way.',
    'email_verified' => 'Your email address is verified.',

    // Password reset
    'reset_password' => 'Reset your password',
    'send_reset_link' => 'Send the reset link',
    'forgot_password_hint' => 'Enter your email address and we will send you a link to set a new password.',
    'reset_link_sent' => 'If that address is registered, a reset link is on its way.',
    'confirm_password_hint' => 'Confirm your password before changing how your account is secured.',
    'confirm' => 'Confirm',

    // One-time codes
    'enter_code' => 'Enter the code',
    'enter_code_from_app' => 'Enter the code from your authenticator app',
    'digit' => 'Digit',
    'verify' => 'Verify',
    'resend_code' => 'Send another code',
    'otp_required' => 'Enter the 6-digit code we sent.',
    'otp_sms' => 'Your CareersPro code is :code. It expires in :minutes minutes. Do not share it.',
    'otp_sent' => 'We have sent a code to :destination.',
    'otp_no_mobile' => 'This account has no verified mobile number, so we cannot send a code. Sign in with your password, then add and verify a mobile number in your profile.',
    'otp_unverified_mobile' => 'The mobile number on this account has not been verified yet. Sign in with your password to verify it.',
    'otp_cooldown' => 'You can request another code at :time.',
    'otp_hourly_cap' => 'Too many codes requested. Try again after :time.',
    'otp_gateway_failed' => 'We could not send a code just now. Sign in with your password, or try again in a few minutes.',
    'otp_expired' => 'That code has expired. Request a new one.',
    'otp_wrong' => 'That code is not correct. You have :attempts attempts left.',

    // Second factor
    'second_factor' => 'Confirm it is you',
    'second_factor_totp' => 'Enter the current code from your authenticator app.',
    'second_factor_sent' => 'We have sent a code to :destination.',
    'second_factor_wrong' => 'That code is not correct.',
    'recovery_code_hint' => 'Lost your device? Enter one of your recovery codes instead.',
    'pending_expired' => 'That sign-in attempt has expired. Start again.',

    // Two-factor settings
    'two_factor' => 'Two-factor authentication',
    'two_factor_required' => 'Your role requires two-factor authentication. Add a method to continue.',
    'two_factor_enforced' => 'Two-factor authentication is required for your role, so your last remaining method cannot be removed.',
    'method' => 'Method',
    'state' => 'State',
    'confirmed' => 'Confirmed',
    'pending' => 'Pending',
    'remove' => 'Remove',
    'no_methods' => 'No methods enrolled yet.',
    'add_factor' => 'Add :factor',
    'factor_password' => 'password',
    'factor_totp' => 'authenticator app',
    'factor_sms' => 'SMS',
    'factor_email' => 'email',
    'factor_added' => 'That method is now active.',
    'factor_removed' => 'That method has been removed.',
    'factor_unavailable' => 'That method is not available for your account.',
    'factor_last_enforced' => 'Two-factor authentication is required for your role, so this cannot be removed.',
    'scan_qr' => 'Scan this with your authenticator app',
    'totp_manual' => 'Cannot scan? Enter this key instead:',
    'recovery_codes' => 'Recovery codes',
    'recovery_codes_hint' => 'Save these somewhere safe. Each works once, and they are not shown again.',

    // Mobile
    'mobile_invalid' => 'Enter a 10-digit Indian mobile number.',
    'mobile_verified' => 'Your mobile number is verified.',

];
