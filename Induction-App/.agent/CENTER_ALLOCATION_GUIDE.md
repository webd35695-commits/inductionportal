# Center Allocation System - Implementation Summary

## Overview
The center allocation system automatically assigns approved applicants to exam centers based on:
1. **Applicant's preferred/alternative city**
2. **Center-wise post assignments** (which posts are available at which centers)
3. **Center capacity and limits**

## How It Works

### Step 1: Center-Post Assignment (Already Implemented)
Admin assigns posts to centers using the "Center Wise Post Assignment" interface:
- Select a city → Select a center → Assign posts to that center
- Set `max_applicants` limit for each post at each center (optional)
- Data stored in `center_posts` table

### Step 2: Applicant Allocation (Now Implemented)
When you click "Allocate Centers" button:

#### Algorithm Flow:
```
For each approved applicant without a center:
1. Check their PREFERRED CITY
   └─> Find centers in that city that have this POST assigned
       └─> Check if center has capacity
           └─> Assign to center with LOWEST current allocation (load balancing)

2. If no center found in preferred city, check ALTERNATIVE CITY
   └─> Same logic as above

3. If still no center found, SKIP this applicant
```

#### Capacity Checks:
- If `center_posts.max_applicants` is set → use that limit
- Otherwise, use `centers.capacity` as the limit
- Count existing allocations for this post at this center
- Only assign if `current_allocations < max_limit`

### Step 3: Roll Number Generation
Format: `POST-CITY-SEQUENCE`

Example: `LEC-HYD01-0001`
- **POST**: Post abbreviation (e.g., LEC, AP, AST)
- **CITY**: First 3 letters of city + center ID (e.g., HYD01)
- **SEQUENCE**: Sequential number padded to 4 digits

## Files Modified

### 1. `CenterAllocationService.php` ✅
**Main allocation logic:**
- `processAllocations()` - Entry point, processes in batches
- `findAvailableCenter()` - Finds best center for applicant
- `findCenterInCity()` - Checks centers in specific city
- `generateRollNumber()` - Creates unique roll numbers

### 2. `AppliedPostRepository.php` ✅
**Fixed null pointer errors:**
- Added null-safe operators (`?->`) to prevent crashes
- Returns "N/A" for missing candidate profiles

### 3. `center.php` Model✅
**Added:**
- `capacity` and `is_active` to fillable
- `allotments()` relationship

### 4. `CentersAllotment.php` Model ✅
**Added:**
- `post_id` to fillable array

## Database Schema

### Required Tables:
1. **centers** - Exam centers
   - `capacity` (int) - Maximum capacity
   - `is_active` (boolean)

2. **center_posts** - Which posts are at which centers
   - `center_id`
   - `post_id`
   - `max_applicants` (nullable) - Override capacity for specific post

3. **centers_allotments** - Allocation records
   - `applied_post_id`
   - `center_id`
   - `post_id`
   - `roll_number`
   - `status` (allocated/pending/rejected)

## Usage

### For Admins:
1. **Assign Posts to Centers:**
   - Go to "Center Wise Post Assignment"
   - Select city → center → posts
   - Set max applicants if needed

2. **Allocate Applicants:**
   - Go to "Center Allotments"
   - Click "Allocate Centers" button
   - System will process all pending applicants

### Expected Results:
- Applicants assigned to centers in their preferred city
- Load balanced across available centers
- Unique roll numbers generated
- Capacity limits respected

## Error Handling
- Skips applicants if no suitable center found
- Logs failed allocations
- Transaction-safe (rolls back on error)
- Returns statistics: `total`, `allocated`, `skipped`, `failed`

## Next Steps
1. Test the allocation on staging data
2. Add UI to show allocation statistics
3. Consider adding manual override functionality
4. Add allocation preview before confirming
