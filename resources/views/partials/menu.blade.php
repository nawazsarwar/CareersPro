<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li>
                    <select class="searchable-field form-control">

                    </select>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('profile_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.profiles.index") }}" class="nav-link {{ request()->is("admin/profiles") || request()->is("admin/profiles/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.profile.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('photo_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.photos.index") }}" class="nav-link {{ request()->is("admin/photos") || request()->is("admin/photos/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.photo.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('adress_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.adresses.index") }}" class="nav-link {{ request()->is("admin/adresses") || request()->is("admin/adresses/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.adress.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('academic_qualification_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.academic-qualifications.index") }}" class="nav-link {{ request()->is("admin/academic-qualifications") || request()->is("admin/academic-qualifications/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.academicQualification.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('eligibility_test_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.eligibility-tests.index") }}" class="nav-link {{ request()->is("admin/eligibility-tests") || request()->is("admin/eligibility-tests/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.eligibilityTest.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('employment_history_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.employment-histories.index") }}" class="nav-link {{ request()->is("admin/employment-histories") || request()->is("admin/employment-histories/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.employmentHistory.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('foreign_visit_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.foreign-visits.index") }}" class="nav-link {{ request()->is("admin/foreign-visits") || request()->is("admin/foreign-visits/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.foreignVisit.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('referee_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.referees.index") }}" class="nav-link {{ request()->is("admin/referees") || request()->is("admin/referees/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.referee.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('career_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/advertisement-types*") ? "menu-open" : "" }} {{ request()->is("admin/advertisements*") ? "menu-open" : "" }} {{ request()->is("admin/post-types*") ? "menu-open" : "" }} {{ request()->is("admin/posts*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/advertisement-types*") ? "active" : "" }} {{ request()->is("admin/advertisements*") ? "active" : "" }} {{ request()->is("admin/post-types*") ? "active" : "" }} {{ request()->is("admin/posts*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-industry">

                            </i>
                            <p>
                                {{ trans('cruds.career.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('advertisement_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.advertisement-types.index") }}" class="nav-link {{ request()->is("admin/advertisement-types") || request()->is("admin/advertisement-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.advertisementType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('advertisement_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.advertisements.index") }}" class="nav-link {{ request()->is("admin/advertisements") || request()->is("admin/advertisements/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fab fa-adversal">

                                        </i>
                                        <p>
                                            {{ trans('cruds.advertisement.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('post_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.post-types.index") }}" class="nav-link {{ request()->is("admin/post-types") || request()->is("admin/post-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.postType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('post_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.posts.index") }}" class="nav-link {{ request()->is("admin/posts") || request()->is("admin/posts/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.post.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }} {{ request()->is("admin/audit-logs*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }} {{ request()->is("admin/audit-logs*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.auditLog.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('faq_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/faq-categories*") ? "menu-open" : "" }} {{ request()->is("admin/faq-questions*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/faq-categories*") ? "active" : "" }} {{ request()->is("admin/faq-questions*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-question">

                            </i>
                            <p>
                                {{ trans('cruds.faqManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('faq_category_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.faq-categories.index") }}" class="nav-link {{ request()->is("admin/faq-categories") || request()->is("admin/faq-categories/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.faqCategory.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('faq_question_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.faq-questions.index") }}" class="nav-link {{ request()->is("admin/faq-questions") || request()->is("admin/faq-questions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-question">

                                        </i>
                                        <p>
                                            {{ trans('cruds.faqQuestion.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('setting_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/boards*") ? "menu-open" : "" }} {{ request()->is("admin/castes*") ? "menu-open" : "" }} {{ request()->is("admin/categories*") ? "menu-open" : "" }} {{ request()->is("admin/countries*") ? "menu-open" : "" }} {{ request()->is("admin/disability-types*") ? "menu-open" : "" }} {{ request()->is("admin/marital-statuses*") ? "menu-open" : "" }} {{ request()->is("admin/qualification-levels*") ? "menu-open" : "" }} {{ request()->is("admin/postal-codes*") ? "menu-open" : "" }} {{ request()->is("admin/provinces*") ? "menu-open" : "" }} {{ request()->is("admin/religions*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/boards*") ? "active" : "" }} {{ request()->is("admin/castes*") ? "active" : "" }} {{ request()->is("admin/categories*") ? "active" : "" }} {{ request()->is("admin/countries*") ? "active" : "" }} {{ request()->is("admin/disability-types*") ? "active" : "" }} {{ request()->is("admin/marital-statuses*") ? "active" : "" }} {{ request()->is("admin/qualification-levels*") ? "active" : "" }} {{ request()->is("admin/postal-codes*") ? "active" : "" }} {{ request()->is("admin/provinces*") ? "active" : "" }} {{ request()->is("admin/religions*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.setting.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('board_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.boards.index") }}" class="nav-link {{ request()->is("admin/boards") || request()->is("admin/boards/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.board.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('caste_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.castes.index") }}" class="nav-link {{ request()->is("admin/castes") || request()->is("admin/castes/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.caste.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('category_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.category.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('country_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.countries.index") }}" class="nav-link {{ request()->is("admin/countries") || request()->is("admin/countries/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-flag">

                                        </i>
                                        <p>
                                            {{ trans('cruds.country.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('disability_type_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.disability-types.index") }}" class="nav-link {{ request()->is("admin/disability-types") || request()->is("admin/disability-types/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.disabilityType.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('marital_status_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.marital-statuses.index") }}" class="nav-link {{ request()->is("admin/marital-statuses") || request()->is("admin/marital-statuses/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user-friends">

                                        </i>
                                        <p>
                                            {{ trans('cruds.maritalStatus.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('qualification_level_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.qualification-levels.index") }}" class="nav-link {{ request()->is("admin/qualification-levels") || request()->is("admin/qualification-levels/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.qualificationLevel.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('postal_code_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.postal-codes.index") }}" class="nav-link {{ request()->is("admin/postal-codes") || request()->is("admin/postal-codes/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.postalCode.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('province_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.provinces.index") }}" class="nav-link {{ request()->is("admin/provinces") || request()->is("admin/provinces/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-compass">

                                        </i>
                                        <p>
                                            {{ trans('cruds.province.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('religion_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.religions.index") }}" class="nav-link {{ request()->is("admin/religions") || request()->is("admin/religions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.religion.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>