<?php

namespace App\Classes;

class Permissions
{
    public static function attributes(){
        return [
            'لوحة التحكم' => array(
                [
                    'name' => 'يمكنه الإطلاع على الوصول اليوم',
                    'value' => 'see arrive today'
                ],
                [
                    'name' => 'يمكنه الإطلاع على الوصول اليوم',
                    'value' => 'view new reservations'
                ],
                [
                    'name' => 'يمكنه الإطلاع على المغادرة اليوم',
                    'value' => 'view leaving'
                ],
                [
                    'name' => 'يمكنه الإطلاع على احصائيات الشواطئ',
                    'value' => 'view beaches chart'
                ]
            ),
            'القطاعات' => array(
                [
                    'name' => 'يمكنه الإطلاع على القطاعات',
                    'value' => 'can view sectors'
                ],
                [
                    'name' => 'يمكنه الإطلاع على سجلات القطاعات',
                    'value' => 'can view history sectors',
                ],
                [
                    'name' => 'يمكنه إضافة قطاعات جديدة',
                    'value' => 'can add sectors'
                ],
                [
                    'name' => 'يمكنه تعديل القطاعات',
                    'value' => 'can edit sectors'
                ],
                [
                    'name' => 'يمكنه حذف القطاعات',
                    'value' => 'can delete sectors'
                ]
            ),
            'الشواطئ' => array([
                'name' => 'يمكنه الإطلاع على الشواطئ',
                'value' => 'can view beaches',
            ],
            [
                'name' => 'يمكنه الإطلاع على سجلات الشواطئ',
                'value' => 'can view history beaches',
            ],
            [
                'name' => 'يمكنه إضافة شواطئ',
                'value' => 'can add beaches',
            ],
            [
                'name' => 'يمكنه تعديل الشواطئ',
                'value' => 'can edit beaches',
            ],
            [
                'name' => 'يمكنه حذف الشواطئ',
                'value' => 'can delete beaches'
            ]),
            'الوحدات' => array([
                'name' => 'يمكنه الإطلاع على الوحدات',
                'value' => 'can view units',
            ],
            [
                'name' => 'يمكنه الإطلاع على سجلات الوحدات',
                'value' => 'can view history units',
            ],
            [
                'name' => 'يمكنه الإطلاع على المرفقات',
                'value' => 'can add units',
            ],
            [
                'name' => 'يمكنه التحكم في الوحدات',
                'value' => 'can edit units',
            ],
            [
                'name' => 'يمكنه شطب الوحدات',
                'value' => 'can delete units'
            ]),
            'طلبات التأهيل' => array([
                'name' => 'يمكنه الإطلاع على الطلبات',
                'value' => 'can view unit requests',
            ],
            [
                'name' => 'يمكنه التحكم في الطلبات',
                'value' => 'can control unit requests',
            ]),
            'العقود' => array([
                'name' => 'يمكنه الإطلاع على العقود',
                'value' => 'can view contracts',
            ],
            [
                'name' => 'يمكنه فلترة العقود',
                'value' => 'can filter contracts',
            ],
            [
                'name' => 'يمكنه الإطلاع على سجلات العقود',
                'value' => 'can view history contracts',
            ],
            [
                'name' => 'يمكنه إضافة عقود',
                'value' => 'can add contracts',
            ],
            [
                'name' => 'يمكنه الإطلاع على قيمة الإيجار',
                'value' => 'can view rent value',
            ],
            [
                'name' => 'يمكنه الإطلاع على باركوود المستأجر في العقد',
                'value' => 'can view rental barcode',
            ],
            [
                'name' => 'يمكنه الإطلاع على باركوود المرافق في العقد',
                'value' => 'can view with rental barcode',
            ],
            [
                'name' => 'يمكنه عرض العقد',
                'value' => 'can view contract',
            ],
            [
                'name' => 'يمكنه تعديل العقد',
                'value' => 'can edit contract',
            ]),
            'طلبات العقود' => array([
                'name' => 'يمكنه الإطلاع على طلبات العقود',
                'value' => 'can view contract requests',
            ],
            [
                'name' => 'يمكنه التحكم في الطلبات',
                'value' => 'can control contract requests'
            ]),
            'المستثمرين' => array([
                'name' => 'بمكنه الإطلاع على المستثمرين',
                'value' => 'can view clients'
            ],
            [
                'name' => 'يمكنه إضافة مستثمرين',
                'value' => 'can add clients'
            ],
            [
                'name' => 'يمكنة الإطلاع على سجل تحركات المستخدم',
                'value' => 'can view full user history'
            ],
            [
                'name' => 'يمكنه الإطلاع على سجل  تعديل الادارة في المستثمر',
                'value' => 'can view history clients',
            ],
            [
                'name' => 'يمكنه فلترة المستثمرين',
                'value' => 'can filter clients'
            ],
            [
                'name' => 'يمكنه تعديل المستثمرين',
                'value' => 'can edit clients'
            ],
            [
                'name' => 'يمكنه حذف مستثمرين',
                'value' => 'can delete clients'
            ],
            [
                'name' => 'يمكنه التحكم في فتح و غلق العقد للمستثمر',
                'value' => 'can control contract requests for clients'
            ]),
            'التقارير' => array([
                'name' => 'يمكنه الإطلاع على التقارير',
                'value' => 'can view reports'
            ],
            [
                'name' => 'يمكنه فلترة التقارير',
                'value' => 'can filter reports'
            ],
            [
                'name' => 'يمكنه الإطلاع على إجمالى الأرباح',
                'value' => 'can view total'
            ],
            [
                'name' => 'يمكنه الإطلاع على نسبة القطاع',
                'value' => 'can view percentage',
            ],
            [
                'name' => 'يمكنه الإطلاع على أرباح القطاع',
                'value' => 'can view total sector'
            ],
            [
                'name' => 'يمكنه الإطلاع على العقد',
                'value' => 'can view contract in reports'
            ],
            [
                'name' => 'يمكنه الإطلاع على باركوود المستأجر',
                'value' => 'can view rental barcode in reports'
            ],
            [
                'name' => 'يمكنه الإطلاع على باركوود المرافق',
                'value' => 'can view with rental barcode in reports'
            ]),
            'الصلاحيات' => array([
                'name' => 'يمكنه الإطلاع على الصلاحيات',
                'value' => 'can view permissions',
            ],
            [
                'name' => 'يمكنه الإطلاع على سجلات الصلاحيات',
                'value' => 'can view history permissions'
            ],
            [
                'name' => 'يمكنه إضافة مجموعات صلاحيات',
                'value' => 'can add permissions',
            ],
            [
                'name' => 'يمكنه تعديل مجموعات الصلاحيات',
                'value' => 'can edit permissions'
            ],
            [
                'name' => 'يمكنه حذف مجموعات الصلاحيات',
                'value' => 'can delete permissions'
            ]),
            'الإعدادات' => array([
                'name' => 'يمكنه الإطلاع على الإعدادات',
                'value' => 'can view settings',
            ]),
            'السندات' => array([
                'name' => 'يمكنه الإطلاع على السندات',
                'value' => 'can view bonds',
            ],
            [
                'name' => 'يمكنه إضافة سندات',
                'value' => 'can add bond',
            ],
            [
                'name' => 'يمكنه فلترة السندات',
                'value' => 'can filter bonds',
            ],
            [
                'name' => 'يمكنه الإطلاع على السجلات',
                'value' => 'can view history bonds',
            ])
        ];
    }
}
