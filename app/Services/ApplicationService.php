<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Application;

use App\Mail\PlainMail;

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

	public function comment(array $data, Application $application): application
	{
		$plainmail = new PlainMail();
		$success = $plainmail
			->to($application['email'])
			->send($data['comment']);

		if (!$success) {
			return false;
		}

		$application->comment = $data['comment'];
		$application->status = 'Resolved';

		$application->update();

		return $application;
	}

	public function destroy(Application $application)
	{
		return $application->delete();
	}
}
