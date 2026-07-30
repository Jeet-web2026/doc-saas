<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
   use Notifiable;

    protected $table = 'tbl_mast_user_logi';

    /**
     * Primary key is User_UIN, not the default 'id'.
     */
    protected $primaryKey = 'User_UIN';

    protected $fillable = [
        'User_Name',
        'User_Logo',
        'Prmy_Emai',
        'Prmy_Emai_Vefi',
        'Altn_Emai',
        'Altn_Emai_Vefi',
        'Cutr_UIN',
        'ISD_UIN',
        'Prmy_MoNo',
        'Prmy_MoNo_Vefi',
        'OTP_Prmy_MoNo',
        'Altn_MoNo',
        'Altn_MoNo_Vefi',
        'OTP_Altn_MoNo',
        'User_pasw',
        'Pasw_Self_Gntd',
        'Pasw_Rest_By',
        'Pasw_Rest_On',
        'CrBy',
        'Stau_UIN',
    ];

    /**
     * Hide sensitive fields from arrays/JSON.
     */
    protected $hidden = [
        'User_pasw',
        'OTP_Prmy_MoNo',
        'OTP_Altn_MoNo',
    ];

    
    public function getAuthPassword()
    {
        return $this->User_pasw;
    }

    protected $casts = [
        'Prmy_Emai_Vefi' => 'integer',
        'Altn_Emai_Vefi' => 'integer',
        'Pasw_Self_Gntd' => 'integer',
    ];

    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(
            OrganisationDetails::class,
            'tbl_user_orga_rela',
            'User_UIN',
            'Orga_UIN',
            'UIN', 
            'Orga_UIN' 
        )->withPivot(['Vert_UIN', 'Stau_UIN', 'Expy', 'CrOn', 'CrBy', 'MoOn', 'MoBy']);
    }
}
