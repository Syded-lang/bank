<?php

namespace App\Constants;

class FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo()
    {
        // Use public_path() for all paths to ensure files upload to core/public/assets
        // Then FileManager's syncToRootAssets() auto-copies to root /assets directory
        $data['withdrawVerify'] = [
            'path' => public_path('assets/images/verify/withdraw')
        ];
        $data['depositVerify'] = [
            'path'      => public_path('assets/images/verify/deposit')
        ];
        $data['verify'] = [
            'path'      => public_path('assets/verify')
        ];
        $data['default'] = [
            'path'      => public_path('assets/images/default.png'),
        ];
        $data['withdrawMethod'] = [
            'path'      => public_path('assets/images/withdraw/method'),
            'size' => ''
        ];
        $data['ticket'] = [
            'path'      => public_path('assets/support'),
        ];
        $data['logoIcon'] = [
            'path'      => public_path('assets/images/logoIcon'),
        ];
        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['extensions'] = [
            'path'      => public_path('assets/images/extensions'),
            'size'      => '36x36',
        ];
        $data['seo'] = [
            'path'      => public_path('assets/images/seo'),
            'size'      => '1180x600',
        ];
        $data['userProfile'] = [
            'path'      => public_path('assets/images/user/profile'),
            'size'      => '350x300',
        ];
        $data['adminProfile'] = [
            'path'      => public_path('assets/admin/images/profile'),
            'size'      => '400x400',
        ];
        $data['push'] = [
            'path'      => public_path('assets/images/push_notification'),
        ];
        $data['appPurchase'] = [
            'path'      => public_path('assets/in_app_purchase_config'),
        ];
        $data['maintenance'] = [
            'path'      => public_path('assets/images/maintenance'),
            'size'      => '660x325',
        ];
        $data['language'] = [
            'path' => public_path('assets/images/language'),
            'size' => '50x50'
        ];
        $data['gateway'] = [
            'path' => public_path('assets/images/gateway'),
            'size' => ''
        ];
        $data['pushConfig'] = [
            'path'      => public_path('assets/admin'),
        ];
        $data['beneficiaryTransfer'] = [
            'path' => public_path('assets/images/user/transfer/beneficiary')
        ];
        $data['branchStaff'] = [
            'path' => public_path('assets/branch/staff/resume')
        ];
        $data['branchStaffProfile'] = [
            'path'      => public_path('assets/branch/staff/images/profile'),
            'size'      => '400x400',
        ];

        return $data;
    }
}
