<?php

namespace App\CustomFilter;

use App\Enums\AttributeType;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

class AttributeFilter implements Filter
{
	public function __invoke(Builder $query, mixed $value, string $property): void
	{
		$attributes = Attribute::whereIn('name', array_keys((array)$value))->get();
		foreach ($attributes as $attribute) {
			$data = $value[$attribute->name] ?? null;

			if (empty($data)) {
				continue;
			}

			[$operand, $data] = $this->getOperator($data);

			match ($attribute->type->value) {
				AttributeType::TEXT->value => $this->setTextFilter($query, $attribute, $data, $operand),
				AttributeType::NUMBER->value => $this->setNumberFilter($query, $attribute, $data, $operand),
				AttributeType::DATE->value => $this->setDateFilter($query, $attribute, $data, $operand),
				AttributeType::SELECT->value => null, // todo implement
			};
		}
	}

	private function getOperator(string $attribute): array
	{
		if (Str::startsWith($attribute, ['<=', '>=', '!='])) {
			$operand = substr($attribute, 0, 2);
			return [$operand, str_replace($operand, '', $attribute)];
		}

		if (Str::startsWith($attribute, ['<', '>'])) {
			$operand = substr($attribute, 0, 1);
			return [$operand, str_replace($operand, '', $attribute)];
		}

		if (Str::startsWith($attribute, 'LIKE')) {
			return ['LIKE', str_replace('LIKE', '', $attribute)];
		}

		return ['=', $attribute];
	}

	private function setTextFilter(Builder $query, Attribute $attribute, string $data, string $operand): void
	{
		if ($operand === 'LIKE') {
			$data = "%".str_replace(' ', '%', $data)."%";
		} else {
			$operand = '=';
		}

		$this->setFilter($query, $attribute, $data, $operand);
	}

	private function setNumberFilter(Builder $query, Attribute $attribute, int $data, string $operand): void
	{
		$operand = $operand === 'LIKE' ? '=' : $operand;

		$this->setFilter($query, $attribute, $data, $operand);
	}

	private function setDateFilter(Builder $query, Attribute $attribute, string $data, string $operand): void
	{
		if (!Carbon::hasFormat($data, 'Y-m-d')) {
			return;
		}
		$operand = $operand === 'LIKE' ? '=' : $operand;

		$this->setFilter($query, $attribute, $data, $operand);
	}

	private function setFilter(Builder $query, Attribute $attribute, mixed $data, string $operand): void
	{
		$query->whereIn('projects.id', function ($subQuery) use ($attribute, $data, $operand) {
			$subQuery->from('attribute_value')
				->select('entity_id')
				->where([
					['attribute_id', '=', $attribute->id],
					['value', $operand, $data]
				]);
		});
	}
}