# Profile Photo Upload Fix

## Overview
Fixed the profile photo upload functionality in the Candidate Profile page. The photo upload feature is now fully functional with proper validation and two upload options.

## Changes Made

### 1. Enhanced File Input Validation
**Location:** Lines 1381-1417

**Added Validations:**
- **File Type Validation:** Only accepts JPG, JPEG, and PNG files
- **File Size Validation:** Maximum file size of 2MB
- **User Feedback:** Alert messages for validation failures

**Code:**
```javascript
const handleFileUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    // Validate file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
      alert('Please upload a valid image file (JPG or PNG)');
      e.target.value = '';
      return;
    }
    
    // Validate file size (max 2MB)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if (file.size > maxSize) {
      alert('File size must be less than 2MB');
      e.target.value = '';
      return;
    }
    
    // Preview and store file
    const reader = new FileReader();
    reader.onload = (event) => {
      photoPreview.value = event.target.result;
    };
    reader.readAsDataURL(file);
    form.photo = file;
  }
};
```

### 2. Added Dedicated "Save Photo" Button
**Location:** Lines 159-167 (Template)

**Features:**
- Appears only when a photo is selected
- Shows loading state with spinner animation
- Disabled during upload
- Green gradient styling to indicate save action

**Button States:**
- **Default:** "Save Photo" with checkmark icon
- **Loading:** "Saving..." with spinning icon
- **Disabled:** Grayed out and non-clickable during upload

### 3. Added Photo Upload Function
**Location:** Lines 1425-1472

**Functionality:**
```javascript
const savePhoto = async () => {
  // Validates photo is selected
  // Creates FormData with photo and all required profile fields
  // Sends POST request to backend
  // Shows success message
  // Resets file input
  // Handles errors gracefully
};
```

**Key Features:**
- Uses FormData for proper file upload
- Includes all required profile fields to satisfy backend validation
- Uses axios for direct HTTP request
- Provides user feedback (success/error messages)
- Resets form state after successful upload

### 4. Added Upload State Management
**Location:** Line 1119

**New Reactive Variable:**
```javascript
const uploadingPhoto = ref(false);
```

**Purpose:**
- Tracks photo upload progress
- Disables button during upload
- Shows loading spinner
- Prevents duplicate submissions

### 5. Updated Form Initialization
**Location:** Line 1165

**Added:**
```javascript
photo: null,
```

**Purpose:**
- Properly initializes the photo field in the form
- Allows Inertia form to handle file uploads
- Enables photo upload with main form submission

### 6. Updated File Input Accept Attribute
**Location:** Line 157

**Changed:**
```html
<!-- Before -->
accept="image/*"

<!-- After -->
accept="image/jpeg,image/jpg,image/png"
```

**Purpose:**
- Restricts file picker to only show JPG and PNG files
- Provides better user experience
- Prevents users from selecting invalid file types

## Two Ways to Upload Photo

### Option 1: Immediate Upload (Recommended)
1. Click "Upload Photo" button
2. Select a photo (JPG/PNG, max 2MB)
3. Photo preview appears
4. Click "Save Photo" button
5. Photo is uploaded immediately
6. Success message appears

**Advantages:**
- Immediate feedback
- Photo saved separately
- No need to fill other fields
- Can upload photo anytime

### Option 2: Upload with Profile
1. Click "Upload Photo" button
2. Select a photo
3. Fill in other profile fields
4. Click "Save" or "Save & Next" button
5. Photo is uploaded with profile data

**Advantages:**
- Single submission
- All data saved together
- Efficient for new profiles

## Backend Compatibility

The backend controller already supports photo uploads:
- Accepts `photo` file in request
- Stores in `storage/profiles` directory
- Saves path in `candidate_profiles.photo_path` column
- Uses Laravel's file storage system

**Backend Code (Already Exists):**
```php
if ($request->hasFile('photo')) {
    $photoPath = $request->file('photo')->store('profiles', 'public');
}
```

## User Experience Improvements

1. **Clear Validation Messages:** Users know exactly what went wrong
2. **Visual Feedback:** Photo preview shows selected image
3. **Loading States:** Spinner indicates upload in progress
4. **Error Handling:** Graceful error messages if upload fails
5. **Flexible Options:** Two ways to upload based on user preference
6. **File Restrictions:** File picker only shows valid image types

## Photo Guidelines Display

The UI clearly shows photo requirements:
- ✅ Size: 35mm × 45mm (passport size)
- ✅ Format: JPG, PNG (max 2MB)
- ✅ Background: Plain white or light color
- ✅ Face should be clearly visible

## Testing Checklist

- [x] File type validation (JPG, PNG only)
- [x] File size validation (max 2MB)
- [x] Photo preview display
- [x] Immediate photo upload
- [x] Photo upload with profile save
- [x] Loading state during upload
- [x] Success message display
- [x] Error handling
- [x] Remove photo functionality
- [x] Photo persistence after upload

## Technical Details

**File Upload Method:** FormData + Axios
**Accepted Types:** image/jpeg, image/jpg, image/png
**Max Size:** 2MB (2,097,152 bytes)
**Storage Path:** storage/profiles/
**Database Column:** candidate_profiles.photo_path
**Preview Method:** FileReader API (base64)

## Notes

- Photo is optional - users can skip it
- Photo can be updated anytime
- Old photo is replaced when new one is uploaded
- Photo is displayed from `/storage/{photo_path}`
- File validation happens on both frontend and backend
