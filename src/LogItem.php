<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;

/**
 * @property int $id
 * @property string $created
 * @property string $updated
 * @property string $type
 * @property int $userid
 * @property string $msg
 * @property string $receiver_mail
 * @property string $mail_sent_time
 * @property string $confirm_read
 */
class LogItem extends ReactiveObject
{
  /** @var array */
  protected $readOnlyAttributes = [
    'id',
    'updated'
  ];

  /** @var array */
  protected $defaults = [
    'type' => null,
    'msg' => null
  ];
}
