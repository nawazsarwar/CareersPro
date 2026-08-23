# Careers Module Developer Documentation

## Overview
The Careers Module in the AcademicERP application is designed to manage the end-to-end recruitment process. It handles the creation of Advertisements, Posts associated with those Advertisements, Post Types (for templating), and the processing of Application Forms received for these Posts. The system also includes a robust scrutiny mechanism to evaluate applicant eligibility at different stages (initial scrutiny, written test, and interview).

---

## 1. Advertisement Management

### Architecture
*   **Controller**: `app/Http/Controllers/Admin/AdvertisementsController.php`
*   **Model**: `app/Models/Advertisement.php`
*   **Views Directory**: `resources/views/admin/advertisements/`
*   **Database Table**: `careers.advertisements`

### Description
An Advertisement serves as a container for multiple Posts. Each advertisement can be of a specific type (e.g., General or Local) and includes global dates and fee parameters that can act as defaults for its associated posts.

### Key Fields & Behaviors
*   **`title` & `slug`**: The name and URL-friendly identifier for the advertisement.
*   **`type`**: Selected from predefined types: `General`, `Local`.
*   **`advertisement_url` / `document_id`**: Links to the official advertisement PDF/document uploaded via Spatie MediaLibrary.
*   **`default_fee`, `default_open_date`, `default_reg_end_date`, `default_payment_end_date`**: Serve as fallback or initial values when creating posts under this advertisement.
*   **Media Collection**: The `document` collection is used to store and retrieve the primary advertisement file.

### Views (Index & Show)
*   **Index View**: Uses DataTables (`yajra/laravel-datatables`) via AJAX. Displays the title (linked to the show page), slug, date, type, URL, status, and default dates/fees.
*   **Show View**: Displays the detailed attributes of the advertisement along with a list of related Posts.

### Bulk Document Generation (Export Services)
The controller provides an export service specifically for Advertisement applications:
*   **Method**: `exportApplicationsByAdvertisement(Request $request, Advertisement $advertisement)`
*   **Functionality**: Triggers an Excel download containing all application forms received for all posts under this advertisement using the `AdvertisementApplicationFormExport` class.
*   **Memory Configuration**: Dynamically sets `memory_limit` and `max_execution_time` to `-1` to handle large datasets.

---

## 2. Post Management

### Architecture
*   **Controller**: `app/Http/Controllers/Admin/PostsController.php`
*   **Model**: `app/Models/Post.php`
*   **Views Directory**: `resources/views/admin/posts/`
*   **Database Table**: `careers.posts`

### Description
Posts represent specific job openings or vacancies under an Advertisement. They contain granular details such as pay level, vacancies, specific timelines, and eligibility criteria (age, experience).

### Key Fields & Behaviors
*   **`advertisement_id` & `posttype_id`**: Foreign keys linking the post to its parent advertisement and its structural type.
*   **`vacancies` & `location`**: Detail the number of openings and the job location.
*   **`pay_level` & `pay_range`**: Compensation details.
*   **Timeline Fields**: 
    *   `open_date`, `reg_end_date`, `payment_end_date`
    *   `admit_card_opening_date`, `admit_card_closing_date`, `test_date`
    *   `interview_letter_opening_date`, `interview_letter_closing_date`, `interview_date`
*   **Eligibility Logic**: The `Post` model contains critical helper methods to validate applicant eligibility at the time of payment deadline:
    *   `calculateApplicantAge($dob)`
    *   `calculateApplicantExperience($workExperiences)`
    *   `isAgeOverLimit($ageData)`
    *   `isExperienceBelowLimit($totalDays)`

### Views (Index & Show)
*   **Index View**: Uses DataTables via AJAX. Lists posts with their linked Advertisement, Post Type, title, vacancies, pay details, and status.
*   **Show View**: Displays comprehensive post details. Importantly, this view typically renders the related `ApplicationForms` associated with the post at the bottom of the page, allowing administrators to review applicants directly from the post view.

### Bulk Document Generation (Export Services)
*   **Method**: `exportApplicationsByPost(Request $request, Post $post)`
*   **Functionality**: Triggers an Excel download containing all application forms received for this specific post using the `PostApplicationFormExport` class.

---

## 3. Post Type Management

### Architecture
*   **Controller**: `app/Http/Controllers/Admin/PostTypesController.php`
*   **Model**: `app/Models/PostType.php`
*   **Views Directory**: `resources/views/admin/postTypes/`

### Description
Post Types act as templates/categories that dictate how a Post behaves and what documents/templates it uses.

### Key Fields & Behaviors
*   **`name`**: The identifier of the post type.
*   **`pdf_template`, `admit_card_template`, `interview_letter_template`**: Define the specific view templates used to generate PDFs for applicants associated with this post type.
*   **`default_selection_method`**: Determines the standard process (e.g., Test, Interview, or Both) for selecting candidates for this type of post.

---

## 4. Application Forms & Scrutiny Mechanism

### Architecture
*   **Controller**: `app/Http/Controllers/Admin/ApplicationFormsController.php`
*   **Model**: `app/Models/ApplicationForm.php`
*   **Database Table**: `careers.applicationforms`

### Description
Application Forms represent the candidates' submissions. The schema handles denormalized basic details (for historical integrity) alongside dynamic fields.

### Key Fields & Behaviors
*   **Denormalized Snapshot Data**: Fields like `advertisement_title`, `post_title`, `name`, `email`, `dob`, etc., are regenerated from basic details to ensure that if a post's title changes, the historical application record remains accurate.
*   **Application Lifecycle**: Controlled by flags:
    *   `review` = 1, `submitted` = 0: Under review by the user.
    *   `submitted` = 1: User has finalized the application.
    *   `paid` = 1: Payment is successful.
    *   `hardcopy_received`: Acknowledges receipt of physical documents.

### Scrutiny and Eligibility Updation Mechanism
The scrutiny process is a multi-stage evaluation mechanism managed via the `updateEligibility` method in `ApplicationFormsController.php`. It handles three distinct stages of eligibility, triggered by AJAX requests from the Post's Application Forms view:

1.  **Initial Scrutiny**:
    *   `scrutiny_eligible` (1/0)
    *   `scrutiny_remark`
    *   Automatically timestamps (`scrutiny_updated_at`) and tracks the reviewer (`scrutiny_updated_by`).

2.  **Written Test Eligibility**:
    *   `written_test_eligible` (1/0)
    *   `written_test_eligibility_remark`
    *   Automatically timestamps and tracks the reviewer.

3.  **Interview Eligibility**:
    *   `interview_eligible` (1/0)
    *   `interview_eligibility_remark`
    *   Automatically timestamps and tracks the reviewer.

The `updateEligibility` endpoint dynamically updates only the payload provided in the request, returning a JSON response with the updated state to refresh the UI without reloading the page.

---

## Note on Screenshots
*(Placeholder for UI Screenshots)* 
> **Developer Note**: Browser subagent initialization failed due to `playwright: Protocol error (Browser.setDownloadBehavior): Browser context management is not supported` on the provided environment. You can manually insert screenshots for the `Advertisements List`, `Advertisement Details`, `Posts List`, `Post Details (with Applications Table)`, and `Export Applications Buttons` in this section.
