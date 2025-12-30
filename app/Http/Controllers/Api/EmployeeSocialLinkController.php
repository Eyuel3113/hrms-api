<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeSocialLink;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Employee Social Links
 * APIs for managing employee social media and professional links
 */
class EmployeeSocialLinkController extends Controller
{
    /**
     * List Social Links
     * 
     * Get all social links for an employee.
     * 
     * @urlParam employeeId string required Employee UUID
     */
    public function index($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $socialLinks = $employee->socialLinks;

        return response()->json([
            'message' => 'Social links fetched successfully',
            'data' => $socialLinks
        ]);
    }

    /**
     * Add Social Link
     * 
     * Add a new social link for an employee.
     * 
     * @urlParam employeeId string required Employee UUID
     * @bodyParam platform string required Platform ('linkedin', 'github', 'twitter', 'facebook', 'instagram', 'portfolio', 'telegram', 'slack', 'whatsapp', 'skype', 'behance', 'dribbble', 'other')
     * @bodyParam url string required Full URL
     */
    public function store(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // Bulk Creation
        if ($request->has('links')) {
            $request->validate([
                'links' => 'required|array',
                'links.*.platform' => [
                    'required',
                    'in:linkedin,github,twitter,facebook,instagram,portfolio,telegram,slack,whatsapp,skype,behance,dribbble,other',
                    'distinct', // Prevent duplicates in the request array
                    Rule::unique('employee_social_links', 'platform')->where(function ($query) use ($employeeId) {
                        return $query->where('employee_id', $employeeId);
                    })
                ],
                'links.*.url' => [
                    'required',
                    'url',
                    'max:500',
                    'distinct', 
                    Rule::unique('employee_social_links', 'url')->where(function ($query) use ($employeeId) {
                        return $query->where('employee_id', $employeeId);
                    })
                ],
            ]);

            $createdLinks = [];
            \DB::transaction(function() use ($employee, $request, &$createdLinks) {
                foreach($request->links as $link) {
                    $createdLinks[] = $employee->socialLinks()->create($link);
                }
            });

            return response()->json([
                'message' => 'Social links added successfully',
                'data' => $createdLinks
            ], 201);
        }

        // Single Creation (Backward Compatibility)
        $validated = $request->validate([
            'platform' => [
                'required',
                'in:linkedin,github,twitter,facebook,instagram,portfolio,telegram,slack,whatsapp,skype,behance,dribbble,other',
                Rule::unique('employee_social_links')->where(fn ($query) => $query->where('employee_id', $employeeId))
            ],
            'url' => [
                'required',
                'url',
                'max:500',
                Rule::unique('employee_social_links')->where(fn ($query) => $query->where('employee_id', $employeeId))
            ],
        ]);

        $socialLink = $employee->socialLinks()->create($validated);

        return response()->json([
            'message' => 'Social link added successfully',
            'data' => $socialLink
        ], 201);
    }

    /**
     * Update Social Link
     * 
     * Update an existing social link.
     * 
     * @urlParam employeeId string required Employee UUID
     * @urlParam linkId string required Social Link UUID
     * @bodyParam platform string Platform
     * @bodyParam url string URL
     */
    public function update(Request $request, $employeeId, $linkId)
    {
        $employee = Employee::findOrFail($employeeId);
        $socialLink = $employee->socialLinks()->findOrFail($linkId);

        $validated = $request->validate([
            'platform' => [
                'sometimes',
                'in:linkedin,github,twitter,facebook,instagram,portfolio,telegram,slack,whatsapp,skype,behance,dribbble,other',
                Rule::unique('employee_social_links', 'platform')->where(fn ($query) => $query->where('employee_id', $employeeId))->ignore($linkId)
            ],
            'url' => [
                'sometimes',
                'url',
                'max:500',
                Rule::unique('employee_social_links', 'url')->where(fn ($query) => $query->where('employee_id', $employeeId))->ignore($linkId)
            ],
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No valid data provided for update. Please provide platform or url.'
            ], 422);
        }

        $socialLink->update($validated);

        return response()->json([
            'message' => 'Social link updated successfully',
            'data' => $socialLink
        ]);
    }

    /**
     * Delete Social Link
     * 
     * Delete a social link.
     * 
     * @urlParam employeeId string required Employee UUID
     * @urlParam linkId string required Social Link UUID
     */
    public function destroy($employeeId, $linkId)
    {
        $employee = Employee::findOrFail($employeeId);
        $socialLink = $employee->socialLinks()->findOrFail($linkId);
        $socialLink->delete();

        return response()->json([
            'message' => 'Social link deleted successfully'
        ]);
    }
}
