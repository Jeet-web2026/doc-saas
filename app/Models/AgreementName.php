<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgreementName extends Model
{
    protected $table = 'tbl_tran_agreement_names';
    protected $primaryKey = 'Agmt_Name_UIN';

    protected $fillable = [
        'Orga_UIN',
        'Agmt_Catg_UIN',
        'Agmt_Type_UIN',
        'Agmt_Name',
        'CrBy',
    ];

    public function category()
    {
        return $this->belongsTo(AgreementCategories::class, 'Agmt_Catg_UIN', 'Agmt_Catg_UIN');
    }

    public function type()
    {
        return $this->belongsTo(AgreementType::class, 'Agmt_Type_UIN', 'Agmt_Type_UIN');
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(OrganisationDetails::class, 'Orga_UIN', 'Orga_UIN');
    }
}
