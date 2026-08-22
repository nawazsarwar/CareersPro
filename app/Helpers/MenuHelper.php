<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'MENU',
                'items' => [
                    [
                        'icon' => 'dashboard',
                        'name' => 'Dashboard',
                        'path' => 'admin',
                        'route' => 'admin.home',
                    ],
                    [
                        'icon' => 'user',
                        'name' => 'Profiles',
                        'path' => 'admin/profiles',
                        'route' => 'admin.profiles.index',
                    ],
                    [
                        'icon' => 'image',
                        'name' => 'Photos',
                        'path' => 'admin/photos',
                        'route' => 'admin.photos.index',
                    ],
                    [
                        'icon' => 'map-pin',
                        'name' => 'Adresses',
                        'path' => 'admin/adresses',
                        'route' => 'admin.adresses.index',
                    ],
                    [
                        'icon' => 'graduation-cap',
                        'name' => 'Academic Qualifications',
                        'path' => 'admin/academic-qualifications',
                        'route' => 'admin.academic-qualifications.index',
                    ],
                    [
                        'icon' => 'check-circle',
                        'name' => 'Eligibility Tests',
                        'path' => 'admin/eligibility-tests',
                        'route' => 'admin.eligibility-tests.index',
                    ],
                    [
                        'icon' => 'briefcase',
                        'name' => 'Employment Histories',
                        'path' => 'admin/employment-histories',
                        'route' => 'admin.employment-histories.index',
                    ],
                    [
                        'icon' => 'plane',
                        'name' => 'Foreign Visits',
                        'path' => 'admin/foreign-visits',
                        'route' => 'admin.foreign-visits.index',
                    ],
                    [
                        'icon' => 'users',
                        'name' => 'Referees',
                        'path' => 'admin/referees',
                        'route' => 'admin.referees.index',
                    ],
                ]
            ],
            [
                'title' => 'MANAGEMENT',
                'items' => [
                    [
                        'icon' => 'megaphone',
                        'name' => 'Advertisements',
                        'path' => '#',
                        'subItems' => [
                            ['name' => 'Advertisement Types', 'path' => 'admin/advertisement-types', 'route' => 'admin.advertisement-types.index'],
                            ['name' => 'Advertisements', 'path' => 'admin/advertisements', 'route' => 'admin.advertisements.index'],
                            ['name' => 'Post Types', 'path' => 'admin/post-types', 'route' => 'admin.post-types.index'],
                            ['name' => 'Posts', 'path' => 'admin/posts', 'route' => 'admin.posts.index'],
                        ]
                    ],
                    [
                        'icon' => 'shield',
                        'name' => 'User Management',
                        'path' => '#',
                        'subItems' => [
                            ['name' => 'Permissions', 'path' => 'admin/permissions', 'route' => 'admin.permissions.index'],
                            ['name' => 'Roles', 'path' => 'admin/roles', 'route' => 'admin.roles.index'],
                            ['name' => 'Users', 'path' => 'admin/users', 'route' => 'admin.users.index'],
                            ['name' => 'Audit Logs', 'path' => 'admin/audit-logs', 'route' => 'admin.audit-logs.index'],
                        ]
                    ],
                    [
                        'icon' => 'help-circle',
                        'name' => 'FAQ Management',
                        'path' => '#',
                        'subItems' => [
                            ['name' => 'FAQ Categories', 'path' => 'admin/faq-categories', 'route' => 'admin.faq-categories.index'],
                            ['name' => 'FAQ Questions', 'path' => 'admin/faq-questions', 'route' => 'admin.faq-questions.index'],
                        ]
                    ],
                    [
                        'icon' => 'settings',
                        'name' => 'System Settings',
                        'path' => '#',
                        'subItems' => [
                            ['name' => 'Boards', 'path' => 'admin/boards', 'route' => 'admin.boards.index'],
                            ['name' => 'Castes', 'path' => 'admin/castes', 'route' => 'admin.castes.index'],
                            ['name' => 'Categories', 'path' => 'admin/categories', 'route' => 'admin.categories.index'],
                            ['name' => 'Countries', 'path' => 'admin/countries', 'route' => 'admin.countries.index'],
                            ['name' => 'Disability Types', 'path' => 'admin/disability-types', 'route' => 'admin.disability-types.index'],
                            ['name' => 'Marital Statuses', 'path' => 'admin/marital-statuses', 'route' => 'admin.marital-statuses.index'],
                            ['name' => 'Qualification Levels', 'path' => 'admin/qualification-levels', 'route' => 'admin.qualification-levels.index'],
                            ['name' => 'Postal Codes', 'path' => 'admin/postal-codes', 'route' => 'admin.postal-codes.index'],
                            ['name' => 'Provinces', 'path' => 'admin/provinces', 'route' => 'admin.provinces.index'],
                            ['name' => 'Religions', 'path' => 'admin/religions', 'route' => 'admin.religions.index'],
                        ]
                    ],
                ]
            ]
        ];
    }

    public static function getIconSvg($iconName)
    {
        // For simplicity and to avoid cluttering this helper with huge SVGs,
        // we can return a FontAwesome icon or a generic feather icon if SVG is missing.
        // Since TailAdmin uses svg, we provide basic SVGs for the main categories.

        $icons = [
            'dashboard' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.75 3.375H12.375V1.125C12.375 0.525 11.85 0 11.25 0H6.75C6.15 0 5.625 0.525 5.625 1.125V3.375H2.25C1.05 3.375 0 4.425 0 5.625V14.625C0 15.825 1.05 16.875 2.25 16.875H15.75C16.95 16.875 18 15.825 18 14.625V5.625C18 4.425 16.95 3.375 15.75 3.375ZM6.75 1.125H11.25V3.375H6.75V1.125ZM16.875 14.625C16.875 15.225 16.35 15.75 15.75 15.75H2.25C1.65 15.75 1.125 15.225 1.125 14.625V5.625C1.125 5.025 1.65 4.5 2.25 4.5H15.75C16.35 4.5 16.875 5.025 16.875 5.625V14.625Z" fill="currentColor"/></svg>',
            'user' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 8.25C10.875 8.25 12.375 6.75 12.375 4.875C12.375 3 10.875 1.5 9 1.5C7.125 1.5 5.625 3 5.625 4.875C5.625 6.75 7.125 8.25 9 8.25ZM9 2.625C10.2375 2.625 11.25 3.6375 11.25 4.875C11.25 6.1125 10.2375 7.125 9 7.125C7.7625 7.125 6.75 6.1125 6.75 4.875C6.75 3.6375 7.7625 2.625 9 2.625ZM14.0625 11.625C13.2375 10.5375 11.925 9.75 10.3125 9.45C10.05 9.4125 9.7875 9.6 9.7125 9.8625C9.675 10.125 9.8625 10.3875 10.125 10.4625C11.55 10.725 12.6375 11.3625 13.275 12.225C13.6875 12.75 13.875 13.3875 13.875 14.0625V15.375H4.125V14.0625C4.125 13.3875 4.3125 12.75 4.725 12.225C5.3625 11.3625 6.45 10.725 7.875 10.4625C8.1375 10.3875 8.325 10.125 8.2875 9.8625C8.2125 9.6 7.95 9.4125 7.6875 9.45C6.075 9.75 4.7625 10.5375 3.9375 11.625C3.3375 12.375 3 13.2375 3 14.0625V15.9375C3 16.2375 3.2625 16.5 3.5625 16.5H14.4375C14.7375 16.5 15 16.2375 15 15.9375V14.0625C15 13.2375 14.6625 12.375 14.0625 11.625Z" fill="currentColor"/></svg>',
            'image' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>',
            'map-pin' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
            'graduation-cap' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>',
            'check-circle' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
            'briefcase' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
            'plane' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.2-1.1.6L3 8l6 5.5-3 3-3-1-2 2 4 4 2-2-1-3 3-3 5.5 6 1.2-.7c.4-.2.7-.6.6-1.1z"></path></svg>',
            'users' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            'megaphone' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>',
            'shield' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
            'help-circle' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
            'settings' => '<svg class="fill-current" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
        ];

        return $icons[$iconName] ?? $icons['dashboard'];
    }
}
