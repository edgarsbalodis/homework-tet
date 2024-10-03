<?php

namespace Backscreen\Movies\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Backscreen\Movies\Models\Movie;
use Backscreen\Helpers\Logger;
use Backscreen\Movies\Http\Requests\StoreMovieRequest;
use Backscreen\Movies\Http\Resources\MovieResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;


/**
 * Note: For larger and more complex functionality, I would recommended to
 * implement the Service-Repository pattern. This pattern separates
 * the business logic into service classes and database interactions into 
 * repositories. This approach improves maintainability, testability,
 * and scalability of the application, especially when multiple data sources
 * or complex operations are involved.
 * 
 * Example:
 * 
 * - MovieRepository: handles data access operations (find, create, update, delete)
 * - MovieService: contains business logic and interacts with the repository
 * 
 * This way, the controller remains thin and only handles request validation and responses.
 * 
 */
class MoviesController extends Controller
{

    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Display a listing of the movies.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Retrieve all movies from the database
            $movies = Movie::all();
            
            return MovieResource::collection($movies);
        } catch (Exception $e) {
            // Log any exception that occurs for further inspection
            $this->logger->log('error', $e->getMessage());

            // Return a JSON response indicating a server error
            return response()->json(['error' => 'Unable to fetch movies'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created movies in the database.
     * 
     * @param StoreMovieRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMovieRequest $request)
    {
        try {
            // Validate the request data based on the StoreMovieRequest rules
            $moviesData = $request->validated();

            $movies = Movie::create($moviesData);

            return new MovieResource($movies);
        } catch (ValidationException $e) {
            // Handle validation errors and return a 422 response
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            // Log any exception and return a server error response
            $this->logger->log('error', $e->getMessage());

            return response()->json(['error' => 'Unable to create movies'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified movies.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            // Attempt to find the movies by ID, or throw ModelNotFoundException
            $movies = Movie::findOrFail($id);

            return new MovieResource($movies);
        } catch (ModelNotFoundException $e) {
            // Handle case where movies is not found and return a 404 response
            return response()->json(['error' => 'movies not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            // Log the exception and return a server error response
            $this->logger->log('error', $e->getMessage());

            return response()->json(['error' => 'Unable to fetch movies'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }

    /**
     * Update the specified movies in the database.
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            // Find the movies by ID or throw ModelNotFoundException
            $movies = Movie::findOrFail($id);

            // Validate the request data with specific rules
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'director' => 'nullable|string',
                'release_date' => 'nullable|date',
                'genre' => 'nullable|string',
            ]);

            // Update the movies with the validated data
            $movies->update($validatedData);

            // Return the updated movies as a resource
            return new MovieResource($movies);
        } catch (ValidationException $e) {
            // Handle validation errors and return a 422 response
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            // Handle case where movies is not found and return a 404 response
            return response()->json(['error' => 'movies not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Log the exception and return a server error response
            $this->logger->log('error', $e->getMessage());

            return response()->json(['error' => 'Unable to update movies'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified movies from the database.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            // Find the movies by ID or throw ModelNotFoundException
            $movies = Movie::findOrFail($id);

            // Delete the movies from the database
            $movies->delete();

            // Return a 204 No Content response
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'movies not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $this->logger->log('error', $e->getMessage());
            
            return response()->json(['error' => 'Unable to delete movies'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}