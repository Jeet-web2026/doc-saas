<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterVerticalPageName extends Model
{
    protected $table = 'tbl_mast_vert';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     * The table has no created_at / updated_at columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Vert_Grp',
        'Vert_Grup_UIN',
        'vertical',
        'Desc',
        'Vert_Type',
        'redirect_url',
        'path',
        'Data_base_name',
        'Data_base_tbl_name',
        'Data_base_tbl_ntry_mtpr_kb',
        'Stau_UIN',
        'Vert_Icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Vert_Grup_UIN' => 'integer',
        'Data_base_tbl_ntry_mtpr_kb' => 'decimal:2',
        'Stau_UIN' => 'integer',
    ];
}
