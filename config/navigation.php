<?php

return [
    // roles => null means visible to every authenticated user, regardless of role

    ['route' => 'dashboard', 'label' => 'Voucher Management', 'roles' => ['admin','user']],

    // Super Admin only
    ['route' => 'expense_category.index', 'label' => 'Expense Category', 'roles' => ['super_admin']],
    ['route' => 'service_type.index', 'label' => 'Service Group', 'roles' => ['super_admin']],
    ['route' => 'expense_management.index', 'label' => 'Expense Management', 'roles' => ['super_admin']],

    ['route' => 'report', 'label' => 'Report', 'roles' => ['admin','user']],

    // Admin + Super Admin
    ['route' => 'users.index', 'label' => 'Users Management', 'roles' => ['admin', 'super_admin']],

    ['route' => 'boost_types', 'label' => 'Service Type Management', 'roles' => ['admin' , 'super_admin']],
    ['route' => 'categories.index', 'label' => 'Category Management', 'roles' => ['admin']],
    ['route' => 'cms-images.index', 'label' => 'CMS Image', 'roles' => ['admin']],

    // Admin only
    ['route' => 'settings.index', 'label' => 'Site Settings', 'roles' => ['admin']],
    ['route' => 'profit-report.index', 'label' => 'Profit  Report', 'roles' => ['super_admin']],
    ['route' => 'site-profit-report.index', 'label' => 'Total  Report', 'roles' => ['super_admin']],





];
