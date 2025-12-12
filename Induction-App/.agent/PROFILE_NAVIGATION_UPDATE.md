# Candidate Profile Navigation Update

## Overview
Updated the Candidate Profile page to include better navigation flow with "Save" and "Next" buttons across all three sections: Profile Details, Qualifications, and Age Relaxation.

## Changes Made

### 1. Profile Details Section
**Location:** Lines 545-576

**Changes:**
- Modified the action buttons layout from `justify-end` to `justify-between`
- Added two buttons on the right side:
  - **Save** button (Green/Emerald gradient) - Saves the profile without navigation
  - **Save & Next** button (Blue/Indigo gradient) - Saves and moves to Qualifications tab

**Functionality:**
- Cancel button remains on the left
- Save button: Saves profile data and shows success message
- Save & Next button: Saves profile data and automatically switches to the Qualifications tab

### 2. Qualifications Section
**Location:** Lines 738-764

**Changes:**
- Added a new navigation buttons section after the qualifications list
- Includes:
  - **Previous** button (left side) - Returns to Profile Details tab
  - **Next** button (right side) - Moves to Age Relaxation tab

**Functionality:**
- Previous button: Switches to the 'profile' tab
- Next button: Switches to the 'ageRelaxation' tab
- No save action required as qualifications are saved individually

### 3. Age Relaxation Section
**Location:** Lines 1048-1095

**Changes:**
- Completely redesigned the action buttons
- Changed layout from `justify-end` to `justify-between`
- Added four buttons:
  - **Previous** button (left side) - Returns to Qualifications tab
  - **Skip to Dashboard** button - Allows skipping age relaxation (optional)
  - **Save** button (Green/Emerald gradient) - Saves age relaxation data
  - **Save & Go to Dashboard** button (Blue/Indigo gradient) - Saves and redirects to dashboard

**Functionality:**
- Previous button: Switches to the 'qualifications' tab
- Skip to Dashboard: Directly navigates to candidate dashboard without saving
- Save button: Saves age relaxation data and shows success message
- Save & Go to Dashboard: Saves data, shows success message, and redirects to dashboard after 1 second

## New JavaScript Functions Added
**Location:** Lines 1462-1537

### 1. `saveAndNext(nextTab)`
- Validates CNIC length
- Saves profile data via POST request
- On success: Shows success message and switches to the specified tab
- Used by the "Save & Next" button in Profile Details section

### 2. `skipToDashboard()`
- Directly redirects to the candidate dashboard
- No data saving occurs
- Used by the "Skip to Dashboard" button in Age Relaxation section

### 3. `saveAndGoToDashboard()`
- Validates all age relaxation fields if their respective checkboxes are enabled
- Saves age relaxation data via POST request
- On success: Shows success message and redirects to dashboard after 1 second delay
- Used by the "Save & Go to Dashboard" button in Age Relaxation section

## User Experience Improvements

1. **Clear Navigation Flow:** Users can now easily move between sections using Previous/Next buttons
2. **Flexible Saving Options:** Users can choose to save and continue or save and move to the next section
3. **Optional Age Relaxation:** Since age relaxation is not mandatory, users can skip it entirely and go directly to the dashboard
4. **Visual Consistency:** All buttons use consistent styling with gradient backgrounds and icons
5. **Better Button Organization:** Buttons are logically grouped with navigation on the left and action buttons on the right

## Color Coding
- **Green/Emerald Gradient:** Save actions (non-navigating)
- **Blue/Indigo Gradient:** Primary actions with navigation
- **Gray Border:** Secondary actions (Cancel, Previous, Skip)

## Notes
- All navigation maintains scroll position using `preserveScroll: true`
- Success messages are displayed for 3 seconds using toast notifications
- Form validation is maintained across all save operations
- The age relaxation section validates conditional fields based on checkbox states
