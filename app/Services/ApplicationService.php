<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Application;


class ApplicationService
{  
	public function show(Application $application): Application
	{
		return $application;
	}

	public function index(array $data)
	{
		$count = !empty($data['count']) ? $data['count'] : 10;

		$applications = Application::query();
		$applications->when(!empty($data['sortBy']), function ($q) use ($data) {
		    return $q->orderBy($data['sortBy'], 'desc');
		});

		return $applications->paginate($count);
	}

	public function store(array $data): Application
	{
		$application = Application::create($data);

		return $application;
	}

	public function update(array $data, Application $application): application
	{
		$application->update($data);

		return $application;
	}

	public function destroy(Application $application)
	{
		return $application->delete();
	}
}
