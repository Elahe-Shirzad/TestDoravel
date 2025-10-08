<?php

namespace Modules\Bank\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait FormattedDate
{
    /**
     * createdAt
     *
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('created_at', $this->attributes) ||
                    is_null($this->attributes['created_at'])) {
                    return null;
                }

                return verta($this->attributes['created_at'])->format(jdateFormat('datetime_comma'));
            }
        );
    }

    /**
     * updatedAt
     *
     * @return Attribute
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('updated_at', $this->attributes) ||
                    is_null($this->attributes['updated_at'])) {
                    return null;
                }

                return verta($this->attributes['updated_at'])->format(jdateFormat('datetime_comma'));
            }
        );
    }

    /**
     * birthDate
     *
     * @return Attribute
     */
    protected function birthDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('birth_date', $this->attributes) ||
                    is_null($this->attributes['birth_date'])) {
                    return null;
                }

                return verta($this->attributes['birth_date'])->format(jdateFormat('date'));
            },
            set: function ($value) {
                if (is_null($value)) {
                    $this->attributes['birth_date'] = null;
                } else {
                    $this->attributes['birth_date'] = verta()->parse($value)->toCarbon()->format(jdateFormat('date_dash'));
                }

                return $this->attributes['birth_date'];
            }
        );
    }

    /**
     * startedAt
     *
     * @return Attribute
     */
    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('start_date', $this->attributes) ||
                    is_null($this->attributes['start_date'])) {
                    return null;
                }

                return verta($this->attributes['start_date'])->format(jdateFormat('date'));
            },
            set: function ($value) {
                if (is_null($value)) {
                    $this->attributes['start_date'] = null;
                } else {
                    $this->attributes['start_date'] = verta()->parse($value)->toCarbon()->format(jdateFormat('date'));
                }

                return $this->attributes['start_date'];
            }
        );
    }

    /**
     * endAt
     *
     * @return Attribute
     */
    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('end_date', $this->attributes) ||
                    is_null($this->attributes['end_date'])) {
                    return null;
                }

                return verta($this->attributes['end_date'])->format(jdateFormat('date'));
            },
            set: function ($value) {
                if (is_null($value)) {
                    $this->attributes['end_date'] = null;
                } else {
                    $this->attributes['end_date'] = verta()->parse($value)->toCarbon()->format(jdateFormat('date'));
                }

                return $this->attributes['end_date'];
            }
        );
    }

    /**
     * publishedAt
     *
     * @return Attribute
     */
    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('published_at', $this->attributes) ||
                    is_null($this->attributes['published_at'])) {
                    return null;
                }

                return verta($this->attributes['published_at'])->format(jdateFormat());
            },
            set: function ($value) {
                if (is_null($value)) {
                    $this->attributes['published_at'] = null;
                } else {
                    $this->attributes['published_at'] = verta()->parse($value)->toCarbon()->format(jdateFormat('datetime_dash'));
                }

                return $this->attributes['published_at'];
            }
        );
    }

    /**
     * expiredAt
     *
     * @return Attribute
     */
    protected function expiredAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('expired_at', $this->attributes) ||
                    is_null($this->attributes['expired_at'])) {
                    return null;
                }

                return verta($this->attributes['expired_at'])->format(jdateFormat());
            },
            set: function ($value) {
                if (is_null($value)) {
                    $this->attributes['expired_at'] = null;
                } else {
                    $this->attributes['expired_at'] = verta()->parse($value)->toCarbon()->format(jdateFormat('datetime_dash'));
                }

                return $this->attributes['expired_at'];
            }
        );
    }

    /**
     * closedAt
     *
     * @return Attribute
     */
    protected function closedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!array_key_exists('closed_at', $this->attributes) ||
                    is_null($this->attributes['closed_at'])) {
                    return null;
                }

                return verta($this->attributes['closed_at'])->format(jdateFormat('datetime_comma'));
            },
            set: function ($value) {
                if (is_null($value)) {
                    $this->attributes['closed_at'] = null;
                } else {
                    $this->attributes['closed_at'] = verta()->parse($value)->toCarbon()->format(jdateFormat('datetime_dash'));
                }

                return $this->attributes['closed_at'];
            }
        );
    }
}
