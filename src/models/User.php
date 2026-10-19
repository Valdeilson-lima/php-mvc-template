<?php
namespace src\models;

use core\Model;

class User extends Model {
    protected static ?string $table = 'users';
    protected static string $primaryKey = 'id';
}
