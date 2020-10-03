<?php namespace binotby\phpcorefaster;

use binotby\phpcorefaster\db\DbModel;


abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}