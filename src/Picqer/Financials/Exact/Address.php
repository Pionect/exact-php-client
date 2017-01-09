<?php

namespace Picqer\Financials\Exact;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class Address
 *
 * @package Picqer\Financials\Exact
 * @see https://start.exactonline.nl/docs/HlpRestAPIResourcesDetails.aspx?name=CRMAddresses
 * @property Guid $ID
 * @property Guid $Account
 * @property String $AddressLine1
 * @property String $AddressLine2
 * @property String $AddressLine3
 * @property String $City
 * @property Guid $Contact
 * @property string $Country
 * @property string $Fax
 * @property Bool $FreeBoolField_01
 * @property Bool $FreeBoolField_02
 * @property Bool $FreeBoolField_03
 * @property Bool $FreeBoolField_04
 * @property Bool $FreeBoolField_05
 * @property DateTime $FreeDateField_01
 * @property DateTime $FreeDateField_02
 * @property DateTime $FreeDateField_03
 * @property DateTime $FreeDateField_04
 * @property DateTime $FreeDateField_05
 * @property Integer $FreeNumberField_01
 * @property Integer $FreeNumberField_02
 * @property Integer $FreeNumberField_03
 * @property Integer $FreeNumberField_04
 * @property Integer $FreeNumberField_05
 * @property string $FreeTextField_01
 * @property string $FreeTextField_02
 * @property string $FreeTextField_03
 * @property string $FreeTextField_04
 * @property string $FreeTextField_05
 * @property string $Mailbox
 * @property Bool $Main
 * @property string $NicNumber
 * @property string $Notes
 * @property string $Phone
 * @property string $PhoneExtension
 * @property string $Postcode
 * @property string $State
 * @property integer $Type
 * @property Guid $Warehouse
 * */

class Address extends Model
{

    use Query\Findable;
    use Persistance\Storable;

    protected $fillable = [
        'ID',
        'Account',
        'AddressLine1',
        'AddressLine2',
        'AddressLine3',
        'City',
        'Contact',
        'Country',
        'Fax',
        'FreeBoolField_01',
        'FreeBoolField_02',
        'FreeBoolField_03',
        'FreeBoolField_04',
        'FreeBoolField_05',
        'FreeDateField_01',
        'FreeDateField_02',
        'FreeDateField_03',
        'FreeDateField_04',
        'FreeDateField_05',
        'FreeNumberField_01',
        'FreeNumberField_02',
        'FreeNumberField_03',
        'FreeNumberField_04',
        'FreeNumberField_05',
        'FreeTextField_01',
        'FreeTextField_02',
        'FreeTextField_03',
        'FreeTextField_04',
        'FreeTextField_05',
        'Mailbox',
        'Main',
        'NicNumber',
        'Notes',
        'Phone',
        'PhoneExtension',
        'Postcode',
        'State',
        'Type',
        'Warehouse'
    ];

    protected $url = 'crm/Addresses';

}