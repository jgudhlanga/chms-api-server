<?php
namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class FractalServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$fractal = $this->app->make('League\Fractal\Manager');
		/* returns a single row */
		response()->macro('item', function ($item, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {
			$resource = new \League\Fractal\Resource\Item($item, $transformer);

			return response()->json(
				$fractal->createData($resource)->toArray(),
				$status,
				$headers
			);
		});
		/* returns a collection of rows */
		response()->macro('collection', function ($collection, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {
			$resource = new \League\Fractal\Resource\Collection($collection, $transformer);

			return response()->json(
				$fractal->createData($resource)->toArray(),
				$status,
				$headers
			);
		});

		/* the intended data was not found */
		response()->macro('dataNotFound', function ($message = 'Expected Data Not Found',  $status = 300, array $headers = []) {
			return response()->json(
				$message,
				$status,
				$headers
			);
		});
		/* You have made bad request error */
		response()->macro('badRequest', function ($message = 'You have made a bad request, check your input',  $status = 301, array $headers = []) {
			return response()->json(
				$message,
				$status,
				$headers
			);
		});
		/* required fields validation */
		response()->macro('validationError', function ($message = 'Validation failed, check required fields',
													  $status = 302, array $headers = []) {
			return response()->json(
				$message,
				$status,
				$headers
			);
		});
		/* The server could not process your operation, maybe your input is invalid*/
		response()->macro('serverError', function ($message = 'Something went wrong on the server side, maybe your input is invalid', $status = 500, array $headers = []) {
			return response()->json(
				$message,
				$status,
				$headers
			);
		});
		/* Success Message */
		response()->macro('message', function ($message = 'Your operation was successfully done!', $status = 200, array $headers = []) {
			return response()->json(
				$message,
				$status,
				$headers
			);
		});
	}
}
