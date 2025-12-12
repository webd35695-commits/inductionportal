# Photo Upload Error Fix - Troubleshooting Guide

## Issue: "Failed to upload photo. Please try again." Alert

### Root Cause
The photo upload was failing because:
1. **CSRF Token Issue**: Using axios directly bypassed Laravel's CSRF protection
2. **Missing Required Fields**: Backend validation requires all profile fields to be filled
3. **Incorrect Data Format**: FormData wasn't being handled correctly by Inertia

### Solution Applied

#### 1. Switched from Axios to Inertia Router
**Before (Problematic):**
```javascript
const response = await axios.post(route('candidate.profile.update'), formData, {
  headers: {
    'Content-Type': 'multipart/form-data',
  },
});
```

**After (Fixed):**
```javascript
const photoForm = useForm({ /* all fields */ });
photoForm.post(route('candidate.profile.update'), {
  preserveScroll: true,
  onSuccess: () => { /* success handler */ },
  onError: (errors) => { /* error handler */ },
});
```

**Why This Works:**
- Inertia automatically handles CSRF tokens
- Properly formats multipart/form-data
- Maintains Laravel session state
- Better error handling

#### 2. Added Required Field Validation
```javascript
// Validate required fields before upload
if (!form.mobile || !form.gender || !form.dateOfBirth || !form.city || 
    !form.postalAddress || !form.permanentAddress || !form.religion || 
    !form.disabilityStatus || !form.maritalStatus || !form.fatherName) {
  alert('Please fill in all required profile fields before uploading photo.');
  return;
}
```

**Required Fields (from backend validation):**
- ✅ mobile
- ✅ gender
- ✅ dateOfBirth
- ✅ city
- ✅ postalAddress
- ✅ permanentAddress
- ✅ religion
- ✅ disabilityStatus
- ✅ maritalStatus
- ✅ fatherName

#### 3. Improved Error Messages
**Before:**
```javascript
alert('Failed to upload photo. Please try again.');
```

**After:**
```javascript
if (errors.photo) {
  alert(`Photo upload failed: ${errors.photo}`);
} else {
  const errorList = Object.entries(errors)
    .map(([field, message]) => `${field}: ${message}`)
    .join('\n');
  alert(`Failed to upload photo. Errors:\n${errorList}`);
}
```

**Benefits:**
- Shows specific field errors
- Lists all validation failures
- Helps user understand what to fix

#### 4. Used Current Form Values
**Before:**
```javascript
formData.append('name', userDetails?.name || '');
formData.append('gender', userDetails?.candidate_profile?.gender || '');
// ... using userDetails
```

**After:**
```javascript
const photoForm = useForm({
  name: form.name,
  gender: form.gender,
  // ... using current form state
});
```

**Why This Matters:**
- Uses the latest form values (user may have edited them)
- Ensures consistency with displayed data
- Prevents stale data issues

## How to Use Photo Upload Now

### Option 1: Upload Photo After Filling Profile
1. Fill in ALL required profile fields:
   - Father's Name
   - Mobile Number
   - Gender
   - Date of Birth
   - Province, District, City
   - Permanent Address
   - Postal Address
   - Religion
   - Disability Status
   - Marital Status

2. Click "Upload Photo" button
3. Select a photo (JPG/PNG, max 2MB)
4. Click "Save Photo" button
5. Photo uploads successfully!

### Option 2: Upload Photo with Profile Save
1. Fill in all profile fields
2. Upload a photo
3. Click "Save" or "Save & Next" button
4. Photo uploads with profile data

## Common Error Messages and Solutions

### Error: "Please fill in all required profile fields before uploading photo."
**Cause:** One or more required fields are empty

**Solution:**
1. Check all required fields are filled
2. Look for red asterisks (*) marking required fields
3. Fill in any missing information
4. Try uploading photo again

### Error: "Photo upload failed: The photo must be an image."
**Cause:** Invalid file type selected

**Solution:**
1. Only select JPG or PNG files
2. Check file extension is .jpg, .jpeg, or .png
3. Don't upload PDF, Word docs, or other file types

### Error: "Photo upload failed: The photo may not be greater than 2048 kilobytes."
**Cause:** File size exceeds 2MB limit

**Solution:**
1. Compress the image using online tools
2. Resize the image to smaller dimensions
3. Use a different photo that's smaller

### Error: "The mobile field is required."
**Cause:** Mobile number is empty

**Solution:**
1. Scroll to "Personal Information" section
2. Fill in the Mobile Number field
3. Try uploading photo again

### Error: "The city field is required."
**Cause:** City is not selected

**Solution:**
1. Scroll to "Address Information" section
2. Select Province first
3. Select District
4. Select City
5. Try uploading photo again

## Backend Validation Rules

```php
'mobile' => 'required|string|max:15',
'gender' => 'required|string|in:Male,Female',
'dateOfBirth' => 'required|date',
'photo' => 'nullable|image|max:2048',
'city' => 'required|int|max:100',
'postalAddress' => 'required|string|max:255',
'permanentAddress' => 'required|string|max:255',
'religion' => 'required|string|max:50',
'disabilityStatus' => 'required|string|in:Yes,No',
'maritalStatus' => 'required|string|in:Single,Married,Divorced',
'fatherName' => 'required|string|max:100',
```

## Testing Checklist

- [x] CSRF token properly handled
- [x] Required fields validated before upload
- [x] File type validation (JPG, PNG)
- [x] File size validation (max 2MB)
- [x] Specific error messages shown
- [x] Success message on upload
- [x] Photo preview maintained
- [x] Form state preserved
- [x] Loading state during upload
- [x] Button disabled during upload

## Debug Steps

If photo upload still fails:

1. **Open Browser Console** (F12)
2. **Check for errors** in Console tab
3. **Check Network tab** for failed requests
4. **Look at the error response** from the server
5. **Verify all required fields are filled**
6. **Check photo file is valid** (JPG/PNG, under 2MB)

## Key Changes Summary

| Aspect | Before | After |
|--------|--------|-------|
| **HTTP Client** | Axios | Inertia Router |
| **CSRF Handling** | Manual | Automatic |
| **Validation** | None | Pre-upload validation |
| **Error Messages** | Generic | Specific field errors |
| **Data Source** | userDetails | Current form state |
| **Success Rate** | Low | High |

## Notes

- Photo upload now requires profile to be filled first
- This ensures data consistency
- Prevents partial profile submissions
- Better user experience with clear error messages
- CSRF protection properly maintained
