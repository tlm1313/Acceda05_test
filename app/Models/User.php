<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'Dni',
        'email',
        'password',
        'foto_id',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role(){

        return  $this->belongsTo('App\Models\Role');

    }
    public function esAdmin(){

        if($this->role->nombre_rol == 'Administrador'){
            return true;
        }
            return false;


    }
   public function foto()
{
    return $this->belongsTo(Foto::class, 'foto_id')->withDefault([
        'foto' => 'default.png'
    ]);
}
    public function registros()
{
    return $this->hasMany(Registro::class);
}
}
