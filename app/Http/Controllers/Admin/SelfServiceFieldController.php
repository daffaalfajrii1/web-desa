<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfService;
use App\Models\SelfServiceField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SelfServiceFieldController extends Controller
{
    public function index(SelfService $self_service)
    {
        $self_service->load('fields');

        return view('admin.layanan-mandiri.fields.index', [
            'service' => $self_service,
            'items' => $self_service->fields,
        ]);
    }

    public function create(SelfService $self_service)
    {
        return view('admin.layanan-mandiri.fields.create', [
            'service' => $self_service,
            'item' => null,
            'fieldTypes' => SelfServiceField::fieldTypes(),
        ]);
    }

    public function store(Request $request, SelfService $self_service)
    {
        $data = $this->validatedData($request, $self_service);

        $self_service->fields()->create($data);

        return redirect()
            ->route('admin.layanan-mandiri.fields.index', $self_service->id)
            ->with('success', 'Field layanan berhasil ditambahkan.');
    }

    public function edit(SelfService $self_service, SelfServiceField $self_service_field)
    {
        abort_unless($self_service_field->self_service_id === $self_service->id, 404);

        return view('admin.layanan-mandiri.fields.edit', [
            'service' => $self_service,
            'item' => $self_service_field,
            'fieldTypes' => SelfServiceField::fieldTypes(),
        ]);
    }

    public function update(Request $request, SelfService $self_service, SelfServiceField $self_service_field)
    {
        abort_unless($self_service_field->self_service_id === $self_service->id, 404);

        $data = $this->validatedData($request, $self_service, $self_service_field);

        $self_service_field->update($data);

        return redirect()
            ->route('admin.layanan-mandiri.fields.index', $self_service->id)
            ->with('success', 'Field layanan berhasil diperbarui.');
    }

    public function destroy(SelfService $self_service, SelfServiceField $self_service_field)
    {
        abort_unless($self_service_field->self_service_id === $self_service->id, 404);

        $self_service_field->delete();

        return redirect()
            ->route('admin.layanan-mandiri.fields.index', $self_service->id)
            ->with('success', 'Field layanan berhasil dihapus.');
    }

    private function validatedData(
        Request $request,
        SelfService $service,
        ?SelfServiceField $field = null
    ): array {
        $generatedName = Str::of($request->field_name ?: $request->field_label)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->toString();

        if ($generatedName === '' || ! preg_match('/^[a-z]/', $generatedName)) {
            $generatedName = 'field_' . $generatedName;
        }

        $request->merge([
            'field_name' => $generatedName,
        ]);

        $data = $request->validate([
            'field_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique('self_service_fields', 'field_name')
                    ->where('self_service_id', $service->id)
                    ->ignore($field?->id),
            ],
            'field_label' => 'required|string|max:255',
            'field_type' => ['required', Rule::in(array_keys(SelfServiceField::fieldTypes()))],
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'options_text' => 'required_if:field_type,select,radio,checkbox|nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $options = null;

        if (in_array($data['field_type'], ['select', 'radio', 'checkbox'], true)) {
            $options = collect(preg_split('/\r\n|\r|\n/', (string) $request->options_text))
                ->map(fn ($value) => trim($value))
                ->filter()
                ->values()
                ->all();
        }

        return [
            'field_name' => $data['field_name'],
            'field_label' => $data['field_label'],
            'field_type' => $data['field_type'],
            'placeholder' => $data['placeholder'] ?? null,
            'help_text' => $data['help_text'] ?? null,
            'is_required' => $request->boolean('is_required'),
            'options' => $options,
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
