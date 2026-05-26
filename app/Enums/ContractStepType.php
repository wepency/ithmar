<?php

namespace App\Enums;

final class ContractStepType
{
    public const DRAFT_CREATED = 'draft_created';
    public const CREATED = 'create';
    public const UPDATED = 'update';
    public const PHONE_VERIFIED = 'phone_verified';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'reject';
    public const PAYMENT_INITIATED = 'payment_initiated';
    public const PAID = 'paid';
    public const PAY_LATER = 'pay_later';
    public const CANCELLED = 'cancelled';
    public const STATUS_CHANGED = 'status_changed';
    public const CODE_RESENT = 'code_resent';

    public static function labels(): array
    {
        return [
            self::DRAFT_CREATED => 'إنشاء مسودة',
            self::CREATED => 'انشاء العقد',
            self::UPDATED => 'تعديل العقد',
            self::PHONE_VERIFIED => 'تفعيل رقم الجوال',
            self::ACCEPTED => 'قبول العقد',
            self::REJECTED => 'رفض العقد',
            self::PAYMENT_INITIATED => 'بدء الدفع',
            self::PAID => 'تم الدفع',
            self::PAY_LATER => 'دفع لاحق',
            self::CANCELLED => 'إلغاء العقد',
            self::STATUS_CHANGED => 'تغيير الحالة',
            self::CODE_RESENT => 'إعادة إرسال رمز التحقق',
        ];
    }

    public static function label(string $type): string
    {
        return self::labels()[$type] ?? $type;
    }

    public static function all(): array
    {
        return [
            self::DRAFT_CREATED,
            self::CREATED,
            self::UPDATED,
            self::PHONE_VERIFIED,
            self::ACCEPTED,
            self::REJECTED,
            self::PAYMENT_INITIATED,
            self::PAID,
            self::PAY_LATER,
            self::CANCELLED,
            self::STATUS_CHANGED,
            self::CODE_RESENT,
        ];
    }
}
