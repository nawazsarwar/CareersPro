<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The registered event vocabulary (M26 §5).
 *
 * An event name that is not here throws, in every environment. A typo'd event
 * name is not a cosmetic defect: it is a silent hole in a record that CRR Rule
 * 22.4 says may be examined at any point, even after joining.
 */
enum AuditEventName: string
{
    // Identity — M03
    case UserRegistered = 'user.registered';
    case UserEmailVerified = 'user.email_verified';
    case UserLoggedIn = 'auth.login';
    case UserLoggedOut = 'auth.logout';
    case LoginFailed = 'auth.login_failed';
    case PasswordReset = 'auth.password_reset';
    case PasswordChanged = 'auth.password_changed';
    case OtpIssued = 'auth.otp.issued';
    case OtpVerified = 'auth.otp.verified';
    case OtpFailed = 'auth.otp.failed';
    case SecondFactorEnrolled = 'auth.second_factor.enrolled';
    case SecondFactorRemoved = 'auth.second_factor.removed';
    case SecondFactorChallenged = 'auth.second_factor.challenged';
    case MobileVerified = 'profile.mobile_verified';

    // Access — M25
    case RoleAssigned = 'access.role_assigned';
    case RoleRevoked = 'access.role_revoked';
    case ImpersonationStarted = 'access.impersonation_started';
    case ImpersonationEnded = 'access.impersonation_ended';

    // Applications — M05
    case ApplicationSubmitted = 'application.submitted';
    case ApplicationWithdrawn = 'application.withdrawn';
    case EligibilityDecided = 'eligibility.decided';
    case DeficiencyRaised = 'deficiency.raised';
    case DeficiencyRectified = 'deficiency.rectified';

    // Payment — M08
    case OrderCreated = 'payment.order_created';
    case PaymentSettled = 'payment.settled';
    case PaymentCallbackRejected = 'payment.callback_rejected';
    case ReconciliationRun = 'payment.reconciled';
    case RefundRequested = 'payment.refund_requested';

    // Records — generic model lifecycle, M26-R01 and M26-R08
    case ModelCreated = 'model.created';
    case ModelUpdated = 'model.updated';
    case ModelDeleted = 'model.deleted';
    case ModelRestored = 'model.restored';

    // Disclosure — M26-R07 and M26-R09
    case DocumentAccessed = 'document.accessed';
    case SeatsAllocated = 'exam.seats_allocated';
    case ExportGenerated = 'export.generated';
    case CampaignSent = 'communication.campaign_sent';
    case GrievanceRaised = 'grievance.raised';
    case HardcopyDestroyed = 'custody.hardcopy_destroyed';
}
