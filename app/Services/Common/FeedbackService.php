<?php

namespace App\Services\Common;

use App\Models\TranFdbk;

class FeedbackService
{
    public function store(array $data): TranFdbk
    {
        return TranFdbk::create([
            'Orga_UIN'  => $data['orga_uin'],
            'Page_UIN'  => 4,
            'Page_Name' => 'feedback_page',
            'Page_load' => $data['page_load'],
            'Page_Dsin' => $data['page_design'],
            'Page_Oall' => $data['overall'],
            'Fdbk_Type' => $data['feedback_type'],
            'Mesg_Fdbk' => $data['comments'] ?? null,
            'PinCod'    => $data['pincode'],
            'CrBy'      => $data['created_by'],
            'CrOn'      => now()->timestamp,
        ]);
    }
}
