<?php
namespace App\Requests;

interface IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules();
}
