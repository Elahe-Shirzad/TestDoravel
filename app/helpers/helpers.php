<?php

use Illuminate\Http\RedirectResponse;
use Dornica\BladeComponents\UI\Icon\Icon;
use Dornica\BladeComponents\UI\Link\Link;
use Dornica\Foundation\Core\Enums\IsActive;
use Dornica\Foundation\FileManager\Facade\FileManager;
use Dornica\Foundation\FileManager\Models\FileType;
use Illuminate\Auth\Access\Response as AuthAccessResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Bank\Enums\Files\FileType as FileTypeEnum;
use Modules\Bank\Enums\BooleanState;
use Modules\Bank\Enums\WorkFlowType;


if (!function_exists('backWithError')) {
    function backWithError(string $message = null): RedirectResponse
    {
        if (!$message) {
            $message = "عملیات با خطا مواجه شد";
        }

        return back()->withFlash(
            message: __($message),
            type: "error",
        );
    }
}


if (!function_exists('backWithSuccess')) {
    function backWithSuccess(string $message = null): RedirectResponse
    {
        if (!$message) {
            $message = "عملیات  با موفقیت انجام شد";
        }

        return back()->withFlash(
            message: __($message),
            type: "success",
        );
    }
}


if (!function_exists('nationalCodeMaskFormatter')) {
    function nationalCodeMaskFormatter(string $nationalCode): string
    {
        if (
            strlen($nationalCode) !== 10 ||
            !ctype_digit($nationalCode)
        ) {
            throw new InvalidArgumentException('National Code Must Be 10 Digits!');
        }

        return substr($nationalCode, 0, 3) . '-'
            . substr($nationalCode, 3, 6) . '-'
            . substr($nationalCode, 9, 1);
    }
}

if (!function_exists('getFile')) {
    function getFile(?int $fileID): ?object
    {
        return $fileID ? FileManager::get($fileID) : null;
    }
}

if (!function_exists('postalCodeMaskFormatter')) {
    function postalCodeMaskFormatter(?string $postalCode): string
    {
        // Check if the postal code is provided and has exactly 10 digits
        if (
            $postalCode === null ||
            strlen($postalCode) !== 10 ||
            !ctype_digit($postalCode)
        ) {
            throw new InvalidArgumentException('Postal Code Must Be 10 Digits!');
        }

        return substr($postalCode, 0, 5) . '-' . substr($postalCode, 5, 5);
    }
}

if (!function_exists('renderBadgeForEnum')) {
    function renderBadgeForEnum(?string $module = null): Closure
    {
        return function ($value, $entity) use ($module) { // NOSONAR: this function require both $value and $entity
            // Ensure that the value is provided and is a valid enum instance
            if (!$value) {
                return "-";
            }

            // Generate enum name from class name
            $enumName = Str::snake(class_basename($value));
            $prefix = $module ? "{$module}::" : 'basemodule::';

            // Assign class based on the value of the enum
            $class = match ($value->value) {
                3 => 'badge-dark',
                2 => 'badge-secondary',
                1 => 'badge-success',
                0 => 'badge-danger',
                default => 'badge-info',
            };

            // Fetch the translated label
            $label = __("{$prefix}enum.$enumName." . Str::lower($value->name));

            // Return the formatted badge HTML
            return "<span class='text-wrap line-height-normal badge {$class}'>{$label}</span>";
        };
    }
}

if (!function_exists('renderBadgeEnum')) {
    function renderBadgeEnum(array $customColors = [], ?string $module = null): Closure
    {
        return function ($value, $entity) use ($customColors, $module) { // NOSONAR: both $value and $entity are required
            if (!$value) {
                return "-";
            }

            $valueKey = $value->value;
            $class = $customColors[$valueKey] ?? match ($valueKey) {
                3 => 'badge-dark',
                2 => 'badge-secondary',
                1 => 'badge-success',
                0 => 'badge-danger',
                default => 'badge-info',
            };

            $enumName = Str::snake(class_basename($value));
            $translationKey = $module ? "$module::enum.$enumName." . Str::lower($value->name)
                : "general.$enumName." . Str::lower($value->name);

            $label = __($translationKey);

            return sprintf(
                "<span class='text-wrap line-height-normal badge %s'>%s</span>",
                e($class),
                e($label)
            );
        };
    }
}

if (!function_exists('getEnumName')) {
    function getEnumName($enum, $selected_item, $module = null, $isEnum = true)
    {
        $translate = '-';
        $prefix = $module ? "{$module}::" : '';
        if ($selected_item || !$isEnum) {
            $enumValues = $enum::array();
            foreach ($enumValues as $key => $enumValue) {
                $selectedItemValue = is_int($selected_item) ? $selected_item : $selected_item->value;
                if ($key == $selectedItemValue) {
                    $translate = __(
                        $prefix . "enum." .
                        Str::snake(class_basename($enum)) . "." .
                        Str::lower($enumValue)
                    );
                }
            }
        }
        return $translate;
    }
}

if (!function_exists('renderBadgeForIsExpireEnum')) {
    function renderBadgeForIsExpireEnum(): Closure
    {
        return function ($value, $entity) { // NOSONAR: this function require both $value and $entity
            $enumName = Str::snake(class_basename($value));
            if (isset($value)) {
                $class = match ($value->value) {
                    0 => 'badge-success',
                    1 => 'badge-danger',
                    default => 'badge-info',
                };
                $label = __("general.$enumName." . Str::lower($value->name));
                return "<span class='text-wrap line-height-normal badge {$class}'>{$label}</span>";
            } else {
                return "-";
            }
        };
    }
}

if (!function_exists('trimString')) {
    function trimString(
        ?string $text = '',
        int     $size = 30,
        bool    $showAll = false,
        bool    $showIcon = true
    ): string
    {
        if ($size < 1) {
            throw new InvalidArgumentException('Minimum size for trimming a string is 1.');
        }

        $text = sanitizeTextContent($text) ?? '';

        if (mb_strlen($text) <= $size) {
            return $text;
        }

        $tooltipLimit = $showAll ? 1000 : $size;
        $originalText = $text;
        $isTrimmed = mb_strlen($originalText) > $tooltipLimit;

        $displayText = $isTrimmed
            ? mb_substr($originalText, 0, $tooltipLimit) . '...'
            : $originalText;

        if ($showIcon && $isTrimmed) {
            $icon = renderComponent(Icon::class, [
                'tooltip' => htmlspecialchars($originalText, ENT_QUOTES, 'UTF-8'),
                'class' => 'fa-regular fa-info-circle align-middle text-muted'
            ]) ?? '';

            return $displayText . $icon;
        }

        return $displayText;
    }
}

if (!function_exists('sanitizeTextContent')) {
    function sanitizeTextContent(string $text): string
    {
        // Strip HTML tags
        $text = strip_tags($text);

        // Decode HTML entities (e.g. &nbsp;, &quot;)
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Replace all forms of ZWNJ (Unicode or encoded) with a space
        $zwnjVariants = ["\u{200C}", "\xE2\x80\x8C"];
        $text = str_replace($zwnjVariants, ' ', $text);

        return $text;
    }
}

if (!function_exists('numberFormatter')) {
    /**
     * @param mixed $value
     * @return string
     */
    function numberFormatter(mixed $value = 0): string
    {
        if ($value) {
            $value = number_format((float)$value);
        } else {
            $value = 0;
        }
        return $value;
    }
}

if (!function_exists('createLink')) {
    /**
     * @param string $name
     * @param string $route
     * @param array $parameters
     * @param null $tooltip
     * @return string
     */
    function createLink(string $name, string $route, array $parameters = [], $tooltip = null): string
    {
        $route = route($route, $parameters);
        return "<a href=" . $route . " data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='top' title='" . $tooltip . "' target='_blank'>{$name}</a>";
    }
}

if (!function_exists('spanGenerator')) {
    function spanGenerator(array|string $name, $badgeColor = 'primary'): string
    {
        $htmlContent = '';
        if (is_array($name)) {
            foreach ($name as $spanName) {
                $htmlContent .= "<span class='badge badge-$badgeColor'>" . $spanName . "</span>";
            }
        } else {
            $htmlContent = "<span class='badge badge-$badgeColor'>" . $name . "</span>";
        }
        return $htmlContent;
    }
}

if (!function_exists('renderNationalCode')) {
    function renderNationalCode(): Closure
    {
        return function ($value, $model) { // NOSONAR: this function require both $value and $entity
            return nationalCodeMaskFormatter($model->national_code);
        };
    }
}

if (!function_exists('renderPostalCode')) {
    function renderPostalCode(): Closure
    {
        return function ($value, $model) { // NOSONAR: this function require both $value and $entity
            return postalCodeMaskFormatter($model->postal_code);
        };
    }
}

if (!function_exists('convertEnumToArray')) {
    function convertEnumToArray(string $enum, ?string $module = null): array
    {
        $data = [];

        if (
            !class_exists($enum) ||
            !in_array('Dornica\\Foundation\\Core\\Traits\\EnumTools', class_uses($enum))
        ) {
            throw new InvalidArgumentException("The provided enum class must use the EnumTools trait.");
        }

        $enumName = Str::snake(class_basename($enum));

        foreach ($enum::cases() as $case) {
            $baseName = "enum.$enumName." . Str::lower($case->name);
            $name = $module ? "$module::$baseName" : $baseName;
            $data[$case->name] = [
                "value" => $case->value,
                "label" => __($name)
            ];
        }

        return $data;
    }
}

if (!function_exists('backWithSuccess')) {
    /**
     * @param string|null $message
     * @return RedirectResponse
     */
    function backWithSuccess(?string $message = null): RedirectResponse
    {
        if (!$message) {
            $message = __("basemodule::message.add_successfully");
        }
        return back()
            ->withFlash(
                message: $message,
                type: "success",
                title: __("basemodule::general.success")
            );
    }
}

if (!function_exists('backWithError')) {
    /**
     * @param string|null $message
     * @return RedirectResponse
     */
    function backWithError(?string $message = null): RedirectResponse
    {
        if (!$message) {
            $message = __("basemodule::message.error_occurred");
        }
        return back()
            ->withFlash(
                message: $message,
                type: "error",
                title: __("basemodule::general.error")
            );
    }
}

if (!function_exists('convertStringToArray')) {
    /**
     * @param ?string $string
     * @param string $seperator
     * @return array
     */
    function convertStringToArray(?string $string, $seperator = ","): array
    {
        $array = [];
        if ($string) {
            $array = explode($seperator, $string);
        }
        return $array;
    }
}

if (!function_exists('filterValidation')) {
    /**
     * @param array $rules
     * @return \Closure
     */
    function filterValidation(array $rules): Closure
    {
        return function ($value) use ($rules) {
            $validator = Validator::make([
                "value" => $value,
            ], [
                "value" => $rules,
            ]);

            return !$validator->fails();
        };
    }
}

if (!function_exists('numtToEnglish')) {
    function numtToEnglish($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $num = range(0, 9);

        // Replace Persian and Arabic numerals with English numerals
        $string = str_replace(array_merge($persian, $arabic), $num, $string);

        return $string;
    }
}

if (!function_exists('toEnglishNumber')) {
    function toEnglishNumber(string|int|null $value): string
    {
        if (is_int($value)) {
            $value = (string)$value;
        }
        if (empty($value)) {
            $value = '';
        }
//        Persian To English
        $value = str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );
//        Arabic To English
        $value = str_replace(
            ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );
        return $value;
    }
}

if (!function_exists('deepConvertToArray')) {
    function deepConvertToArray($value)
    {
        if ($value instanceof Arrayable) {
            return array_map('deepConvertToArray', $value->toArray());
        }

        if (is_array($value)) {
            return array_map('deepConvertToArray', $value);
        }

        return $value;
    }
}

if (!function_exists('prepareSelectComponentForData')) {
    /**
     * Prepare select component data from various source types.
     *
     * @param mixed $source
     * @param string $labelColumn
     * @param string $valueColumn
     * @param bool $shouldEncryptId
     * @param bool $shouldEncryptValue
     * @param string|null $moduleName
     * @return array
     * @throws ReflectionException|Exception
     */
    function prepareSelectComponentForData(
        mixed   $source,
        string  $labelColumn = 'name',
        string  $valueColumn = 'id',
        bool    $shouldEncryptId = true,
        bool    $shouldEncryptValue = true,
        ?string $moduleName = null,
        mixed   $selectedValue = null,
        ?string $optionDataAttributeName = null,
        ?string $sourceColumnForAttribute = null
    ): array
    {
        // Determine source type
        $sourceIsCollection = is_a($source, 'Illuminate\Database\Eloquent\Collection') ||
            is_a($source, 'Illuminate\Support\Collection');

        $sourceIsEnum = !$sourceIsCollection && isEnum($source);
        $sourceIsModel = !$sourceIsCollection && !$sourceIsEnum && app($source) instanceof Model;

        if (
            !$sourceIsEnum &&
            $sourceIsModel
        ) {
            $collection = $source::all();
        } elseif ($sourceIsCollection) {
            $collection = $source;
        } elseif ($sourceIsEnum) {
            return formatEnumData($source, $moduleName, $shouldEncryptId, $shouldEncryptValue, $selectedValue);
        } else {
            throw new InvalidArgumentException("Input source must be a Model class, a Collection, or an Enum class");
        }

        return formatCollectionData($collection, $labelColumn, $valueColumn, $shouldEncryptId, $optionDataAttributeName, $sourceColumnForAttribute);
    }

    /**
     * Format data for enum source.
     *
     * @param string $source
     * @param string|null $moduleName
     * @param bool $shouldEncryptId
     * @param bool $shouldEncryptValue
     * @param mixed $selectedValue
     * @return array
     */
    function formatEnumData(string $source, ?string $moduleName, bool $shouldEncryptId, bool $shouldEncryptValue, mixed $selectedValue): array
    {
        $enumName = Str::snake(class_basename($source));

        if (
            !class_exists($source) ||
            !in_array('Dornica\\Foundation\\Core\\Traits\\EnumTools', class_uses($source))
        ) {
            throw new InvalidArgumentException("The provided enum class must use the EnumTools trait.");
        }

        $translationPrefix = $moduleName ? "{$moduleName}::" : '';

        return array_map(
            function ($enumCase) use ($enumName, $translationPrefix, $shouldEncryptId, $shouldEncryptValue, $selectedValue) {
                $value = $enumCase->value;

                return [
                    'name' => __("{$translationPrefix}enum.{$enumName}." . Str::lower($enumCase->name)),
                    'id' => $shouldEncryptId ? encryptStaticValue($value) : $value,
                    'value' => $shouldEncryptValue ? encryptStaticValue($value) : $value,
                    'selected' => $enumCase == $selectedValue,
                    'is_active' => true
                ];
            },
            $source::cases()
        );
    }

    /**
     * Format data for collection or model source.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $collection
     * @param string $labelColumn
     * @param string $valueColumn
     * @param bool $shouldEncryptId
     * @return array
     */
    function formatCollectionData(
        $collection,
        string $labelColumn,
        string $valueColumn,
        bool $shouldEncryptId,
        ?string $optionDataAttributeName,
        ?string $sourceColumnForAttribute
    ): array
    {
        return $collection->map(function ($item) use (
            $labelColumn,
            $valueColumn,
            $shouldEncryptId,
            $optionDataAttributeName,
            $sourceColumnForAttribute
        ) {
            dump($labelColumn);
            $value = $item->$valueColumn;
            if ($shouldEncryptId) {
                $value = encryptStaticValue($value);
            }

            $option = [
                'name' => $item->$labelColumn,
                'id' => $value,
                'selected' => false,
                'is_active' => true,
            ];

            if ($optionDataAttributeName && $sourceColumnForAttribute && isset($item->$sourceColumnForAttribute)) {
                $rawValue = $item->$sourceColumnForAttribute;
                $optionDataValue = is_object($rawValue) && method_exists($rawValue, 'value')
                    ? $rawValue->value
                    : $rawValue;

                $option[$optionDataAttributeName] = $optionDataValue;
            }

            return $option;
        })->toArray();
    }
}

if (!function_exists('statusCounterInSideBar')) {
    function statusCounterInSideBar(
        string             $model,
        int|array|null     $columnId = null,
        string             $columnName = 'teacher_status_id',
        bool               $countAll = false,
        string             $section = 'teacher',
        bool               $statusFromCache = true,
        array|Closure|null $conditions = null
    ): int
    {
        if (!class_exists($model)) {
            throw new InvalidArgumentException("Model class '{$model}' does not exist.");
        }

        $statusIds = [];

        if ($statusFromCache) {
            $workflowStatuses = getUserCurrentRoleWorkflow($section);

            if ($workflowStatuses->isEmpty()) {
                return numberFormatter(0);
            }

            $statusIds = $countAll ? ($workflowStatuses['view'] ?? []) : (array)$columnId;
        } elseif (!$countAll) {
            $statusIds = (array)$columnId;
        }

        if (empty($statusIds)) {
            return 0;
        }

        $query = $model::query();

        if (!empty($statusIds)) {
            $query->whereIn($columnName, $statusIds);
        }

        if ($conditions) {
            if ($conditions instanceof Closure) {
                $query->where($conditions);
            } elseif (is_array($conditions)) {
                $query->where($conditions);
            }
        }

        return numberFormatter($query->count());
    }
}

if (!function_exists('getCurrentRouteStatusCode')) {
    function getCurrentRouteStatusCode(): string
    {
        $routeNameArray = explode('.', Route::currentRouteName());
        return end($routeNameArray);
    }
}

if (!function_exists('removeWorkflowCacheBySection')) {
    function removeWorkflowCacheBySection(string $section): void
    {
        $roleId = authenticator()->currentRole()['id'];
        systemStorage()->forget("{$section}.{$roleId}", 'workflows');
    }
}

if (!function_exists('statusFiledsVisibility')) {
    function statusFiledsVisibility(string $model, string $section): bool
    {
        $statusCode = getCurrentRouteStatusCode();

        if (!$statusCode) {
            return false;
        }

        $status = $model::firstWhere('code', $statusCode);
        $workflowStatuses = getUserCurrentRoleWorkflow($section);

        return $status && $workflowStatuses->get('view')?->contains($status->id);
    }
}

if (!function_exists('convertScientificSymbolToNumber')) {
    /**
     * Convert a scientific notation string to its integer representation.
     *
     * @param string|null $scientificNumber The scientific number in the format 'X.YY...E+ZZ' or null.
     * @return string The integer representation of the scientific number or an empty string if invalid.
     */
    function convertScientificSymbolToNumber($scientificNumber): string
    {
        // Validate the input format
        if (
            is_null($scientificNumber) ||
            $scientificNumber === ''
        ) {
            return '';
        } elseif (!strpos($scientificNumber, 'E')) {
            return $scientificNumber;
        }

        // Split the scientific notation into mantissa and exponent
        [$mantissa, $exponent] = explode('E', $scientificNumber);

        // Remove the decimal point from the mantissa
        $mantissa = str_replace('.', '', $mantissa);

        // Calculate the new exponent based on the length of the mantissa
        // Subtract the number of digits after the decimal point from the original exponent
        $exponent = (int)$exponent - (strlen($mantissa) - 1);

        // Create and return the integer representation
        return $mantissa . str_repeat('0', max(0, $exponent));
    }
}


// Check if the function 'convertKBToMB' is already defined to prevent redeclaration errors
if (!function_exists('convertKBToMB')) {

    /**
     * Converts a given size in Kilobytes (KB) to Megabytes (MB).
     *
     * @param float|int $size The size in KB to be converted.
     * @return float|int The size converted to MB.
     */
    function convertKBToMB($size): float|int
    {
        // If size is less than 1024 KB, return it as is, since it's too small to convert to MB.
        // Otherwise, divide the size by 1024 to convert KB to MB.
        return ($size < 1024) ? $size : round($size / 1024, 2);
    }
}

// retrieve an active FileType by code and document type
if (!function_exists('getFileType')) {
    function getFileType($type, $code)
    {
        return FileType::query()
            ->where('code', $code)
            ->where('type', $type)
            ->where('is_active', IsActive::YES)
            ->first()
            ?? FileType::query()
                ->where('code', 'general')
                ->where('type', FileTypeEnum::GENERAL)
                ->where('is_active', IsActive::YES)
                ->first();
    }
}

if (!function_exists('getUploadRequirements')) {
    /**
     * Retrieves file upload requirements (e.g., required status, max file size, allowed mime types)
     * based on the document type, file type, and optional entity details.
     *
     * This function determines requirements dynamically using a mix of default, type-specific, and
     * document-specific configurations while adhering to PHP's maximum file size limits.
     *
     * @param object|null $documentType Document type object containing specific upload settings (optional).
     * @param string $defaultMimes Default mime types allowed if no document-specific types are specified.
     * @param string $defaultRequirement Default requirement status (e.g., 'nullable' or 'required').
     * @param string $type Type of file (e.g., 'image', 'video', 'file') to fetch specific settings.
     * @param string|null $entity Entity class used to retrieve additional requirements (optional).
     * @param mixed|null $entityId ID of the entity to check (optional).
     * @param string|null $entityColumnName Column name in the entity for upload status validation (optional).
     *
     * @return array Associative array with upload settings: 'isRequired', 'maxFileSize', 'mimes'.
     */
    function getUploadRequirements(
        ?object $documentType = null,
        string  $defaultMimes = 'png,jpg,jpeg',
        string  $defaultRequirement = 'nullable',
        string  $type = 'image',
        ?string $entity = null,
        ?int    $entityId = null,
        ?string $entityFileRelation = null
    ): array
    {
        // Retrieve PHP and application-wide maximum file size limits.
        $phpMaxFileSize = parseSize(config('dornica-app.file_manager.php_max_file_size')); // PHP max file size(function return KB).
        $defaultMaxFileSize = config('dornica-app.file_manager.max_file_size'); // App-configured max file size(KB).

        // Define type-specific settings for 'image', 'video', and 'file'(KB in DB).
        $typeSettings = [
            'image' => [
                'maxSize' => getSettingOption('max_image_size'),
                'mimes' => getSettingOption('valid_image_extension'),
            ],
            'video' => [
                'maxSize' => getSettingOption('max_video_size'),
                'mimes' => getSettingOption('valid_video_extension'),
            ],
            'file' => [
                'maxSize' => getSettingOption('max_file_size'),
                'mimes' => getSettingOption('valid_file_extension'),
            ]
        ];

        // Determine the requirement based on entity-specific conditions, if provided.
        if ($entity && $entityId && $entityFileRelation) {
            $entityRequirement = optional($entity::find($entityId))?->$entityFileRelation === null ? 'required' : 'nullable';
        }

        // Apply type-specific settings if available.
        if (isset($typeSettings[$type])) {
            $defaultMaxFileSize = $typeSettings[$type]['maxSize'] ?? $defaultMaxFileSize;
            $defaultMimes = $typeSettings[$type]['mimes'] ?? $defaultMimes;
        }

        // Initialize document-specific settings to defaults.
        $documentTypeRequired = $defaultRequirement;
        $documentTypeMimes = $defaultMimes;
        $documentTypeMaxFileSize = min($defaultMaxFileSize, $phpMaxFileSize);

        // Override with document-specific settings, if provided.
        if ($documentType) {
            $documentTypeRequired = ($documentType->is_required->value == BooleanState::YES->value) ? 'required' : 'nullable';
            $documentTypeMimes = $documentType->allowed_extensions ?? $defaultMimes;
            $documentTypeMaxSizeToKB = parseSize($documentType->max_size);
            $documentTypeMaxFileSize = $documentTypeMaxSizeToKB > $phpMaxFileSize ? $documentTypeMaxFileSize : $documentTypeMaxSizeToKB;
        }

        // Finalize the required status, combining entity and document settings.
        $documentTypeRequired = (((isset($entityRequirement) && $entityRequirement === 'required') || !isset($entityRequirement)) && $documentTypeRequired === 'required') ? 'required' : 'nullable';

        // Return the finalized upload requirements.
        return [
            'isRequired' => $documentTypeRequired,
            'maxFileSize' => (int)$documentTypeMaxFileSize, // should be KB
            'mimes' => normalizeString($documentTypeMimes),
        ];
    }
}

if (!function_exists('normalizeString')) {
    /**
     * Removes spaces, underscores, and hyphens from a string.
     *
     * @param string $text
     * @return string
     */
    function normalizeString(string $text): string
    {
        return str_replace([' ', '_', '-'], '', $text);
    }
}

if (!function_exists('parseSize')) {
    /**
     * Convert a size string (e.g., "5G", "10M", "2048") into kilobytes.
     * If no or invalid unit is provided, assumes the value is in bytes.
     *
     * @param string $size Size string with optional valid unit (P, T, G, M, K, B).
     * @return float|int The size in kilobytes.
     */
    function parseSize(string $size): float|int
    {
        $size = trim($size);

        // Separate number and optional unit
        preg_match('/^(\d+)([ptgmkb])?$/i', $size, $matches);

        $value = isset($matches[1]) ? (int)$matches[1] : 0;
        $unit = isset($matches[2]) && in_array(strtolower($matches[2]), ['p', 't', 'g', 'm', 'k', 'b'])
            ? strtolower($matches[2])
            : 'b'; // default to bytes if unit is missing or invalid

        return match ($unit) {
            'p' => $value * 1024 * 1024 * 1024 * 1024,
            't' => $value * 1024 * 1024 * 1024,
            'g' => $value * 1024 * 1024,
            'm' => $value * 1024,
            'k' => $value,
            'b' => $value / 1024,
        };
    }
}

if (!function_exists('formatBytes')) {
    /**
     * Converts a byte value into a human-readable format (e.g., KB, MB, GB).
     *
     * @param float|int $bytes
     * @param int $precision
     * @param bool $persianTranslate
     * @return string
     */
    function formatBytes(float|int $bytes, int $precision = 2, bool $persianTranslate = false): string
    {
        $latinUnits = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $persianUnits = ['بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت', 'پتابایت'];

        if ($bytes <= 0) {
            return '0';
        }

        $pow = min((int)log($bytes, 1024), count($latinUnits) - 1);
        $value = $bytes / (1024 ** $pow);
        $units = $persianTranslate ? $persianUnits : $latinUnits;

        return round($value, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('mapUploadRequirementsToRules')) {
    function mapUploadRequirementsToRules(array $requirements): array
    {
        return array_filter([
            $requirements['isRequired'],
            !empty($requirements['mimes']) ? 'mimes:' . $requirements['mimes'] : null,
            isset($requirements['maxFileSize']) ? 'max:' . $requirements['maxFileSize'] : null
        ]);
    }
}

// Check if the function 'checkAndGetEnumValue' is already defined to avoid redeclaration
if (!function_exists('checkAndGetEnumValue')) {

    /**
     * Checks if a given value is valid for a specified enum class.
     * If the value is null, returns a default of 0.
     * If the value is not null, verifies it is within the enum values,
     * otherwise throws an exception.
     *
     * @param string $enum Enum class name.
     * @param int|null $value Value to check.
     *
     * @return int The validated enum value or default (0) if null.
     *
     * @throws \InvalidArgumentException if the value is not in the enum values.
     */
    function checkAndGetEnumValue(string $enum, ?int $value): int
    {
        if (
            !class_exists($enum) ||
            !in_array('Dornica\\Foundation\\Core\\Traits\\EnumTools', class_uses($enum))
        ) {
            throw new InvalidArgumentException("The provided enum class must use the EnumTools trait.");
        }

        // Retrieve all possible values from the enum class
        $values = $enum::values();

        // Return default (0) if value is null
        if (is_null($value)) {
            return 0;
        }

        // Validate the value is in the enum values, or throw an exception if not
        if (in_array($value, $values, true)) {
            return $value;
        }

        throw new \InvalidArgumentException('Value is not in the enum values!');
    }
}

if (!function_exists('renderImage')) {
    function renderImage(string $disk = 'public'): Closure
    {
        return function ($value, $entity) use ($disk) { // NOSONAR: this function require both $value and $entity
            try {
                if ($value) {
                    // $info = ($disk == "public") ? getLocalFile($value) : getDynamicSFTPFile($value);
                    return "<img class='rounded-image' src='" . FileManager::url($value) . "'/>";
                }
                return "<img class='rounded-image' src='" . asset("assets/images/default.jpg") . "'/>";
            } catch (Exception $exception) {
                return "-";
            }
        };
    }
}

if (!function_exists('renderUrl')) {
    function renderUrl(
        $value,
        ?string $showPermission = null,
        ?string $editPermission = null,
        array $showParameters = [],
        array $editParameters = []
    )
    {

        try {
            if (
                $showPermission &&
                hasAccess($showPermission)
            ) {
                $result = createLink(
                    name: $value,
                    route: $showPermission,
                    parameters: $showParameters
                );
            } elseif (
                $editPermission &&
                hasAccess($editPermission)
            ) {
                $result = createLink(
                    name: $value,
                    route: $editPermission,
                    parameters: $editParameters
                );
            } else {
                $result = $value;
            }
            return $result;
        } catch (Exception $exception) {
            return "-";
        }
    }
}

if (!function_exists('formatMaxSizeMessage')) {
    /**
     * Generate a dynamic max size validation message.
     *
     * @param string $attribute The attribute being validated.
     * @param int $maxSize The max size in KB.
     * @return string Translated message.
     */
    function formatMaxSizeMessage(string $attribute, int $maxSize): string
    {
        $formattedSize = $maxSize >= 1024
            ? round($maxSize / 1024, 2) . ' مگابایت'
            : $maxSize . ' کیلوبایت';

        return __("The :attribute can't be more than :maxSize.", [
            'attribute' => $attribute,
            'maxSize' => $formattedSize,
        ]);
    }
}

if (!function_exists('checkExistIsDefault')) {
    function checkExistIsDefault(
        $model,
        $hasParent = false,
        $parentFieldName = null,
        $parentId = null,
        $ignore = null,
        $modelId = null
    )
    {
        return $model::where("is_default", BooleanState::YES)
            ->where('is_active', IsActive::YES)
            ->when($modelId, function ($query) use ($modelId) {
                $query->where("id", $modelId);
            })
            ->when($ignore, function ($query) use ($ignore) {
                $query->where("id", "<>", $ignore);
            })
            ->when($hasParent, function ($query) use ($parentFieldName, $parentId) {
                $query->where($parentFieldName, $parentId);
            })->exists();
    }
}

/**
 * @param int $statusId
 * @return bool
 */
if (!function_exists('changeable')) {
    function changeable(int $statusId, string $section)
    {
        $workflowStatuses = getUserCurrentRoleWorkflow($section);
        return $workflowStatuses->get('change')?->contains($statusId);
    }
}

/**
 * @param int $statusId
 * @return bool
 */
if (!function_exists('settable')) {
    function settable(int $statusId, string $section)
    {
        $workflowStatuses = getUserCurrentRoleWorkflow($section);
        return $workflowStatuses->get('set')?->contains($statusId);
    }
}

if (!function_exists('convertPersianToEnglish')) {
    function convertPersianToEnglish($string)
    {
        $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persianDigits, $englishDigits, $string);
    }
}
if (!function_exists('getMimeTypeFromExtension')) {
    function getMimeTypeFromExtension($extension)
    {
        $mime_types = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "gif" => "image/gif",
            "bmp" => "image/bmp",
            "webp" => "image/webp",
            "svg" => "image/svg+xml",
            "ico" => "image/vnd.microsoft.icon",

            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "css" => "text/css",
            "js" => "application/javascript",
            "json" => "application/json",
            "xml" => "application/xml",
            "csv" => "text/csv",

            "zip" => "application/zip",
            "rar" => "application/x-rar-compressed",
            "tar" => "application/x-tar",
            "7z" => "application/x-7z-compressed",
            "gz" => "application/gzip",

            "mp3" => "audio/mpeg",
            "wav" => "audio/wav",
            "ogg" => "audio/ogg",

            "mp4" => "video/mp4",
            "avi" => "video/x-msvideo",
            "mov" => "video/quicktime",
            "mkv" => "video/x-matroska",

            "doc" => "application/msword",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "xls" => "application/vnd.ms-excel",
            "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "ppt" => "application/vnd.ms-powerpoint",
            "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
        ];

        // تبدیل به حروف کوچک و جستجو در آرایه
        return $mime_types[strtolower($extension)] ?? "application/octet-stream";
    }
}

if (!function_exists('diffrenceOfTwoArrays')) {
    function diffrenceOfTwoArrays(array $arrayOne, array $arrayTwo)
    {
        $deletedStatusesChange = array_diff($arrayOne, $arrayTwo);
        $newStatusesChange = array_diff($arrayTwo, $arrayOne);
        return ["deleted" => $deletedStatusesChange, "inserted" => $newStatusesChange];
    }
}

if (!function_exists('convertExtensionsToDotFormat')) {
    /**
     * @param $extensions
     * @return string
     */
    function convertExtensionsToDotFormat($extensions): string
    {
        return implode(',', array_map(fn($extension) => '.' . $extension, explode(',', $extensions)));
    }
}

if (!function_exists('getUserCurrentRoleWorkflow')) {
    /**
     * @param $section
     * @return Collection
     */
    function getUserCurrentRoleWorkflow($section): Collection
    {
        $roleId = authenticator()->currentRole()['id'];
        $workflows = systemStorage()->get("{$section}.{$roleId}", 'workflows');
        return collect($workflows ?? []);
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile(
        $module,
        $field,
        $dbField,
        $fileTypeCode,
        $fileType,
        $entity,
        $isPublic = true
    )
    {
        $fileType = getFileType($fileType, $fileTypeCode);

        if (!$fileType) {
            return null;
        }

        FileManager::request($field)
            ->fileType($fileType)
            ->module($module)
            ->entity($entity, $dbField)
            ->{$isPublic ? 'asPublic' : 'asPrivate'}()
            ->process();
    }
}

if (!function_exists('parseDate')) {
    /**
     * @param string|null $date
     * @param string $format
     * @return string|null
     * @throws Exception
     */
    function parseDate(?string $date, string $format = "Y-m-d H:i:s"): ?string
    {
        return $date ? verta()->parse($date)->toCarbon()->format($format) : null;
    }
}

if (!function_exists('parsedUserAgentInfo')) {
    function parsedUserAgentInfo(string $userAgent): array
    {
        // Define OS patterns
        $osPatterns = [
            '/Windows NT 10.0/' => 'Windows 10',
            '/Windows NT 6.3/' => 'Windows 8.1',
            '/Windows NT 6.1/' => 'Windows 7',
            '/Mac OS X ([\d_]+)/' => fn($m) => 'macOS ' . str_replace('_', '.', $m[1]),
            '/Android ([\d.]+)/' => fn($m) => 'Android ' . $m[1],
            '/iPhone OS ([\d_]+)/' => fn($m) => 'iOS ' . str_replace('_', '.', $m[1]),
            '/Linux/' => 'Linux',
        ];

        // Define Browser patterns
        $browserPatterns = [
            '/Edg\/([\d.]+)/' => fn($m) => 'Edge ' . $m[1],
            '/OPR\/([\d.]+)/' => fn($m) => 'Opera ' . $m[1],
            '/Chrome\/([\d.]+)/' => fn($m) => 'Chrome ' . $m[1],
            '/Firefox\/([\d.]+)/' => fn($m) => 'Firefox ' . $m[1],
            '/Version\/([\d.]+).*Safari/' => fn($m) => 'Safari ' . $m[1],
        ];

        // Define Platform logic
        $platform = 'Desktop';
        if (stripos($userAgent, 'Mobile') !== false || stripos($userAgent, 'Android') !== false || stripos($userAgent, 'iPhone') !== false) {
            $platform = 'Mobile';
        } elseif (stripos($userAgent, 'iPad') !== false || stripos($userAgent, 'Tablet') !== false) {
            $platform = 'Tablet';
        }

        // Match OS
        $os = '-';
        foreach ($osPatterns as $pattern => $result) {
            if (preg_match($pattern, $userAgent, $matches)) {
                $os = is_callable($result) ? $result($matches) : $result;
                break;
            }
        }

        // Match Browser
        $browser = '-';
        foreach ($browserPatterns as $pattern => $result) {
            if (preg_match($pattern, $userAgent, $matches)) {
                $browser = is_callable($result) ? $result($matches) : $result;
                break;
            }
        }

        return [
            'os' => $os,
            'browser' => $browser,
            'platform' => $platform,
        ];
    }
}

if (!function_exists('getStartDefaultValue')) {
    function getStartDefaultValue(bool $isFirstStatus): int
    {
        return $isFirstStatus ? BooleanState::YES->value : BooleanState::NO->value;
    }
}

if (!function_exists('checkDatesOverlap')) {
    function checkDatesOverlap(string $modelClass, $startDate, $endDate, $roleId, $ignoreId = null): bool
    {
        $startDate = \Hekmatinasser\Verta\Facades\Verta::parse($startDate)->datetime()->format('Y-m-d');
        $endDate = $endDate ? \Hekmatinasser\Verta\Facades\Verta::parse($endDate)->datetime()->format('Y-m-d') : null;

        $query = $modelClass::query()
            ->where("role_id", $roleId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query
                    ->where(function ($subquery) use ($startDate) {
                        $subquery->where("start_date", "<=", $startDate)
                            ->where("end_date", ">=", $startDate);
                    })
                    ->orWhere(function ($subquery) use ($endDate) {
                        if ($endDate) {
                            $subquery->where("start_date", "<=", $endDate)
                                ->where("end_date", ">=", $endDate);
                        }
                    })
                    ->orWhere(function ($subquery) use ($startDate, $endDate) {
                        $subquery->where("start_date", ">", $startDate);
                        if ($endDate) {
                            $subquery->where("end_date", "<", $endDate);
                        }
                    });
            });

        if ($ignoreId) {
            $query->where("id", "<>", $ignoreId);
        }

        return $query->exists();
    }
}

if (!function_exists('jdateFormat')) {
    function jdateFormat(string $type = 'default'): string
    {
        return match ($type) {
            'date' => 'Y/m/d',
            'date_dash' => 'Y-m-d',
            'time' => 'H:i:s',
            'datetime' => 'Y/m/d H:i:s',
            'datetime_comma' => 'Y/m/d , H:i:s',
            'reverse_datetime_comma' => 'H:i:s , Y/m/d',
            'datetime_dash' => 'Y-m-d H:i:s',
            'datetime_minute' => 'Y/m/d H:i',
            'datetime_minute_with_zero' => 'Y/m/d H:i:00',
            default => 'Y/m/d , H:i'
        };
    }
}

if (!function_exists('checkPolicy')) {
    /**
     * @param $ability
     * @param $entity
     * @return AuthAccessResponse
     */
    function checkPolicy($ability, $entity): AuthAccessResponse
    {
        $user = auth(config('dornica-app.default_guard'))->user();
        return Gate::forUser($user)->inspect($ability, $entity);
    }
}

if (!function_exists('linkUserInfo')) {
    function linkUserInfo(
        ?string $name,
        ?string $code,
        string  $route = '#',
        string  $target = '_blank',
        string  $variant = 'primary',
        bool    $linked = true,
        ?string $classes = null
    ): string
    {
        if (!$name) {
            return '';
        }

        $maskedCode = $code ? nationalCodeMaskFormatter(nationalCode: $code) : null;

        $elements = sprintf(
            '<span class="%s">%s</span>%s',
            $classes,
            $name,
            $maskedCode ? "<span class='dir-ltr text-left ms-1'>({$maskedCode})</span>" : ''
        );

        if ($linked && hasAccess($route)) {
            return renderComponent(
                component: Link::class,
                attributes: [
                    'href' => $route,
                    'target' => $target,
                    'variant' => $variant,
                    'class' => 'd-flex align-items-center',
                ],
                slots: $elements
            );
        }

        return "<div class='d-flex align-items-center'>{$elements}</div>";
    }
}

if (!function_exists('stateBadgeVariant')) {
    /**
     * Return a bootstrap-like badge variant (color) for a given state.
     *
     * @param int|string $value
     * @param array|null $customColors [value => color]
     * @return string
     */
    function stateBadgeVariant($value, ?array $customColors = null): string
    {
        if ($customColors && array_key_exists($value, $customColors)) {
            return $customColors[$value];
        }

        return match ((int)$value) {
            1 => 'success',
            0 => 'danger',
            default => 'secondary',
        };
    }
}

if (!function_exists('renderSelectComponentData')) {
    /**
     * @param mixed $source
     * @param string $labelColumn
     * @param string $valueColumn
     * @param bool $shouldEncryptValue
     * @param string|null $moduleName
     * @param string|null $prefix
     * @param array<string> $extraAttributes
     * @return array
     */
    function renderSelectComponentData(
        mixed        $source,
        string|array $labelColumn = 'name',
        string       $valueColumn = 'id',
        bool         $shouldEncryptValue = true,
        ?string      $moduleName = null,
        ?string      $prefix = null,
        array        $extraAttributes = []
    ): array
    {
        $sourceIsEnum = false;

        $sourceIsModel = false;

        $sourceIsCollection = isCollection($source);

        if (!$sourceIsCollection) {
            $sourceIsEnum = isEnum($source);

            if (!$sourceIsEnum) {
                $sourceIsModel = app($source) instanceof Model;
            }
        }

        if (!$sourceIsEnum && $sourceIsModel) {
            $collection = $source::all();
        } elseif (!$sourceIsEnum && $sourceIsCollection) {
            $collection = $source;
        } elseif (enum_exists($source)) {
            $enumName = Str::snake(class_basename($source));
            $translationPrefix = is_null($prefix) ? ($moduleName ? "$moduleName::" : '') : "$prefix/";

            return array_map(
                function ($enumCase) use ($enumName, $translationPrefix, $shouldEncryptValue) {
                    $value = $enumCase->value;

                    if ($shouldEncryptValue) {
                        $value = encryptValue($value);
                    }

                    return [
                        'name' => __("{$translationPrefix}enum.$enumName." . Str::lower($enumCase->name)),
                        'id' => $value,
                        'selected' => false,
                        'is_active' => true
                    ];
                },
                $source::cases()
            );
        } else {
            throw new InvalidDataSourceException("Input source must be a Model class, a Collection, or an Enum class");
        }

        // format the collection data if it's not an enum
        $formattedData = [];

        foreach ($collection as $item) {
            $isCollectionItem = isCollection($item);
            $value = $isCollectionItem ? $item->get($valueColumn) : $item->$valueColumn;

            if ($shouldEncryptValue) {
                $value = encryptValue($value);
            }

            $data = [
                'name' => is_array($labelColumn)
                    ? collect($labelColumn)
                        ->map(fn($col) => $isCollectionItem ? $item->get($col) : $item->$col)
                        ->implode(' ')
                    : ($isCollectionItem ? $item->get($labelColumn) : $item->$labelColumn),
                'id' => $value,
                'selected' => false,
                'is_active' => true
            ];

            // Append extra attributes if any
            foreach ($extraAttributes as $attribute) {
                $data[$attribute] = $isCollectionItem ? $item->get($attribute) : $item->$attribute;
            }

            $formattedData[] = $data;
        }

        return $formattedData;
    }
}

if (!function_exists('prepareSelectOptionsWithFallback')) {
    /**
     * Prepares select component data with fallback for selected items not marked as active (which have been selected before deactivation).
     *
     * @param string $modelClass The fully qualified model class (e.g., App\Models\Province::class)
     * @param string $labelColumn The column to be used as the label (e.g., 'title')
     * @param array|int|null $selectedIds Single or multiple IDs to include even if inactive
     * @param string|null $sortColumn Column name to order by
     * @return array
     * @throws InvalidDataSourceException|InvalidEncryptionTypeException
     */
    function prepareSelectOptionsWithFallback(
        string         $source,
        string         $labelColumn = 'title',
        array|int|null $selectedIds = null,
        ?string        $sortColumn = null
    ): array
    {
        $query = $source::query()->where(function ($q) use ($selectedIds) {
            $q->where('is_active', IsActive::YES);

            if (!is_null($selectedIds)) {
                $selectedIds = is_array($selectedIds) ? $selectedIds : [$selectedIds];
                $q->orWhereIn('id', $selectedIds);
            }
        });

        if (!is_null($sortColumn)) {
            $query->orderBy($sortColumn);
        }

        return prepareSelectComponentData(source: $query->get(), labelColumn: $labelColumn);
    }
}

if (!function_exists('cacheWorkflowStatusesForRole')) {
    /**
     * cacheWorkflowStatusesForRole
     *
     * @param bool $forceUpdate
     * @return void
     */
    function cacheWorkflowStatusesForRole(bool $forceUpdate = false): void
    {
        $currentRoleId = authenticator()->currentRole()['id'];
        $configWorkflows = config('workflow');

        foreach ($configWorkflows as $section => $workflow) {
            $storageKey = "{$section}.{$currentRoleId}";

            if ($forceUpdate || !systemStorage()->has($storageKey, 'workflows')) {
                $statuses = (new WorkFlowService($workflow))->getAllWorkFlowStatuses();
                systemStorage()->set($storageKey, 'workflows', $statuses);
            }
        }
    }
}

if (!function_exists('makeFileValidationRules')) {
    function makeFileValidationRules(string $fieldKey, array $fileTypeRequirements): array
    {
        return FileManager::validation()
            ->required($fileTypeRequirements['isRequired'] === 'required')
            ->key($fieldKey)
            ->rules([
                'mimes:' . $fileTypeRequirements['mimes'],
                'max:' . $fileTypeRequirements['maxFileSize'],
            ])
            ->make();
    }
}

if (!function_exists('groupBodyTooltip')) {
    function groupBodyTooltip(string $value, ?string $title, ?string $valueClass): string
    {
        $titleHtml = $title ? "<span class='d-block'>{$title}</span>" : '';
        $valueHtml = "<span class='d-block {$valueClass}'>{$value}</span>";

        return <<<HTML
            <div data-bs-toggle="tooltip" data-bs-placement="top">
                {$titleHtml}{$valueHtml}
            </div>
        HTML;
    }
}

function getFileMediaType(?object $file): ?string
{
    $mimeType = optional($file)->mime_type;

    if (filled($mimeType) && Str::startsWith($mimeType, ['image/', 'video/'])) {
        return Str::before($mimeType, '/'); // returns 'image' or 'video'
    }

    return null;
}

if (!function_exists('separatePrefix')) {
    /**
     * Separate the first three characters of a string with a dash.
     *
     * Example:
     *   separatePrefix('01133270787') => '011-33270787'
     *
     * @param string|null $value
     * @return string|null
     */
    function separatePrefix(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        return strlen($value) > 3
            ? substr($value, 0, 3) . '-' . substr($value, 3)
            : $value;
    }
}

if (!function_exists('convertToPersian')) {
    /**
     * @param $datetime
     * @param string $format
     * @return string
     */
    function convertToPersian($datetime, $format = 'Y/m/d'): string
    {
        if (is_null($datetime)) {
            return '-';
        }
        return verta($datetime)->format($format);
    }
}

if (!function_exists('renderColor')) {
    /**
     * @return \Closure
     */
    function renderColor(): Closure
    {
        return function ($value, $entity) { // NOSONAR: this function require both $value and $entity
            if (!$value) {
                $value = '#00000000';
            }
            return sprintf(
                "<span class='d-inline-block rounded-circle bg-primary'
                    style='width: 20px; height: 20px; background-color: %s !important'>
                </span>",
                htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
            );
        };
    }
}

if (!function_exists('renderBadgeForRelation')) {
    function renderBadgeForRelation(
        string|array $relations,
        string|array $column = 'name',
        string       $empty_relation_text = '-',
        string       $badgeColor = 'badge-primary'
    ): Closure
    {
        return function ($value, $model) use ($relations, $column, $empty_relation_text, $badgeColor) { // NOSONAR: this function require both $value and $entity
            try {
                // Ensure $relations is an array.
                $relations = (array)$relations;

                // Traverse through each relation for nested relations.
                foreach ($relations as $relation) {
                    $relatedModel = $model->$relation()->withTrashed()->first();
                    if (!$relatedModel) {
                        return $empty_relation_text; // If relation is null or not found.
                    }
                    $model = $relatedModel;
                }

                // Collect values from columns.
                $columns = (array)$column;
                $name = collect($columns)
                    ->map(fn($col) => $model->$col ?? null) // Safely access columns.
                    ->filter() // Remove null or empty values.
                    ->implode(' '); // Concatenate with spaces.

                // Return badge if name exists, otherwise empty text.
                return $name
                    ? "<span class='text-wrap line-height-normal badge $badgeColor'>{$name}</span>"
                    : $empty_relation_text;
            } catch (Exception $exception) {
                // Optionally log exception here if needed.
                return $empty_relation_text;
            }
        };
    }
}

if (!function_exists('defaultPivotMeta')) {
    function defaultPivotMeta(array $extra = []): array
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $userId = auth(config('dornica-app.default_guard'))->id();

        return array_merge([
            'created_at' => $now,
            'updated_at' => $now,
            'created_by' => $userId,
            'updated_by' => $userId,
        ], $extra);
    }
}

if (!function_exists('syncModelResources')) {
    /**
     * @param $model
     * @param array $newResources
     * @param string $section
     * @param string $relation
     * @param string $resourceModel
     * @return void
     */
    function syncModelResources($model, array $newResources, string $section, string $relation, string $resourceModel): void
    {
        $newResourceUrls = array_column($newResources, 'url');
        $currentResourceUrls = $model->$relation->pluck('url')->toArray();

        $diff = diffrenceOfTwoArrays($currentResourceUrls, $newResourceUrls);

        // 1) Delete removed
        if (!empty($diff['deleted'])) {
            $model->$relation()->whereIn('url', $diff['deleted'])->delete();
        }

        $resourceMap = collect($newResources)->keyBy('url');

        // 2) Update existing
        $commonUrls = array_intersect($currentResourceUrls, $newResourceUrls);
        foreach ($commonUrls as $index => $url) {
            if (!$resourceMap->has($url)) {
                continue;
            }

            $resource = $resourceMap[$url];

            // TODO:: add sort update after add type number to tag-collector item
            $model->$relation()
                ->where('url', $url)
                ->update([
                    'title' => $resource['title'],
                    'url' => $resource['url'],
                    'url_target' => $resource['url_target'],
//                    'sort'       => $resource['sort'],
                    'is_active' => $resource['is_active']
                ]);
        }

        // 3) Insert new
        if (!empty($diff['inserted'])) {
            $resourcesData = [];

            foreach ($diff['inserted'] as $index => $url) {
                if (!$resourceMap->has($url)) {
                    continue;
                }

                $resource = $resourceMap[$url];

                $resourcesData[] = array_merge([
                    "{$section}_id" => $model->id,
                    'title' => $resource['title'],
                    'url' => $resource['url'],
                    'url_target' => $resource['url_target'],
                    'sort' => $index + 1,
                    'is_active' => $resource['is_active'],
                ], defaultPivotMeta());
            }

            if (!empty($resourcesData)) {
                $resourceModel::insert($resourcesData);
            }
        }
    }
}

if (!function_exists('syncModelCategories')) {
    /**
     * @param $model
     * @param array $newCategoryIds
     * @param string $section
     * @param string $relation
     * @param string $categoryModel
     * @return void
     */
    function syncModelCategories($model, array $newCategoryIds, string $section, string $relation, string $categoryModel): void
    {
        $currentCategoryIds = $model->$relation->pluck('id')->toArray();
        $diff = diffrenceOfTwoArrays($currentCategoryIds, $newCategoryIds);

        if (!empty($diff['deleted'])) {
            $model->$relation()->wherePivotIn("{$section}_category_id", $diff['deleted'])->detach();
        }

        if (!empty($diff['inserted'])) {
            $newCategories = $categoryModel::whereIn('id', $diff['inserted'])->get();
            $model->$relation()->attach($newCategories, defaultPivotMeta());
        }
    }
}

if (!function_exists('publishedAtFormat')) {
    /**
     * @param $entity
     * @return array[]
     */
    function publishedAtFormat($entity, $statusRelation): array
    {
        $result = [
            [
                'text' => $entity->published_at ?? '-',
                'dir' => 'ltr',
            ]
        ];

        $hasValidExpiry = $entity->$statusRelation?->is_publish == BooleanState::YES;

        if ($hasValidExpiry) {
            $result[] = [
                'text' => __('basemodule::field.has_expire'),
                'badge' => true,
                'badgeVariant' => 'danger',
                'badgeAppearance' => 'light',
            ];
        }

        return $result;
    }
}

if (!function_exists('handleSearchAndResetPage')) {

    /**
     * Handle search term persistence + paginator reset.
     *
     * @param string|null $search
     * @param string $moduleName
     * @param string $routeName
     * @param array $routeParams
     * @return RedirectResponse|null
     */
    function handleSearchAndResetPage(
        ?string $search,
        string  $sectionName,
        string  $routeName,
        array   $routeParams = []
    ): ?RedirectResponse
    {
        $keyBase = "table_component_search.$sectionName";           // short, unique namespace
        $search = trim((string)$search);         // null ⇒ '' + strip spaces
        $prev = (string)Session::get("$keyBase.current", '');

        // If nothing changed → stay on current page
        if ($search === $prev) {
            return null;
        }

        // -- Persist OR forget ------------------------------------------------
        if ($search === '') {                       // user cleared the box
            Session::forget([
                "$keyBase.current",
                "$keyBase.old",
            ]);
        } else {
            Session::put("$keyBase.old", $prev ?: null);
            Session::put("$keyBase.current", $search);
        }

        // -- Build redirect without `page` -----------------------------------
        $params = $routeParams;
        if ($search !== '') {
            $params['search'] = $search;            // keep term in URL
        }

        return redirect()->route($routeName, $params);
    }
}

if (!function_exists('commentStatusVariant')) {
    /**
     * Get a status-based class with optional prefix.
     *
     * @param int $status The status index.
     * @param string|null $prefix Optional class prefix like 'text-bg', 'bg', or null for raw value.
     * @return string
     */
    function commentStatusVariant(int $status, ?string $prefix = null): string
    {
        $variants = ['info', 'success', 'danger'];
        $variant = $variants[$status] ?? 'secondary';

        return $prefix ? $prefix . '-' . $variant : $variant;
    }
}

if (!function_exists('jalaliToGregorian')) {

    /**
     * Convert a Jalali (Persian) date string to Gregorian (Carbon) date.
     *
     * @param string $jalaliDateString Jalali date as string, e.g. "1404/06/08"
     * @param string|null $format Optional output format, e.g. "Y-m-d" or "Y-m-d H:i:s".
     *                           If null, a Carbon instance is returned.
     * @return string|\Carbon\Carbon Returns formatted date string or Carbon instance based on $format
     */
    function jalaliToGregorian(string $jalaliDateString, ?string $format = 'Y-m-d')
    {
        $vertaDate = \Hekmatinasser\Verta\Verta::parse($jalaliDateString);

        $carbon = $vertaDate->datetime();

        return $format ? $carbon->format($format) : $carbon;
    }
}


//if (!function_exists('localizor')) {
//
//    function localizor(): Localization
//    {
//        return app('localization');
//    }
//}
//
//if (!function_exists('getPortal')) {
//    /**
//     * @return mixed
//     */
//    function getPortal(): mixed
//    {
//        return localizor()->getCurrentPortal();
//    }
//}


//if (!function_exists('countRecord')) {
//
//    function countRecord(): int
//    {
//        $bank=\Modules\Bank\Models\Bank::
//    }
//}


if (!function_exists('cacheWorkflowStatusesForRole')) {
    /**
     * cacheWorkflowStatusesForRole
     *
     * @param bool $forceUpdate
     * @return void
     */
    function cacheWorkflowStatusesForRole(bool $forceUpdate = false): void
    {
        $currentRoleId = authenticator()->currentRole()['id'];
        $configWorkflows = config('workflow');

        foreach ($configWorkflows as $section => $workflow) {
            $storageKey = "{$section}.{$currentRoleId}";

            if ($forceUpdate || !systemStorage()->has($storageKey, 'workflows')) {
                $statuses = (new WorkFlowService($workflow))->getAllWorkFlowStatuses();
                systemStorage()->set($storageKey, 'workflows', $statuses);
            }
        }
    }
}


