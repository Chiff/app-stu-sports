<?php
/**
 * Field
 */
namespace App\Models\Netgrif;

/**
 * Field
 */
class Field {

    /** @var ObjectId $id */
    public $id;

    /** @var Component $component */
    public $component;

    /** @var I18nString $description */
    public $description;

    /** @var object $format */
    public $format;

    /** @var string $importId */
    public $importId;

    /** @var FieldLayout $layout */
    public $layout;

    /** @var int $length */
    public $length;

    /** @var I18nString $name */
    public $name;

    /** @var int $order */
    public $order;

    /** @var I18nString $placeholder */
    public $placeholder;

    /** @var string $stringId */
    public $stringId;

    /** @var string $type */
    public $type;

    /** @var object $value */
    public $value;

    /** @var View $view */
    public $view;

}
