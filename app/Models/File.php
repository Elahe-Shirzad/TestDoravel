<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blog\Models\Blog;
use Modules\BlogCategory\Models\BlogCategory;
use Modules\Book\Models\Book;
use Modules\Book\Models\SubjectContent;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseContent;
use Modules\Course\Models\CourseMember;
use Modules\CourseCategory\Models\CourseCategory;
use Modules\Link\Models\Link;
use Modules\LinkCategory\Models\LinkCategory;
use Modules\Question\Models\Question;
use Modules\TicketCategory\Models\TicketCategory;
use Modules\TicketDepartment\Models\TicketDepartment;

/**
 * Class File
 *
 * @property int $id
 * @property string $name
 * @property string|null $uploader_type
 * @property int|null $uploader_id
 * @property int $file_type_id
 * @property int|null $file_disk_id
 * @property string|null $file_disk_name
 * @property string $mime_type
 * @property string $extension
 * @property int $size
 * @property string $path
 * @property string $full_month
 * @property string $original_name
 * @property bool $is_private
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property bool $is_deleted
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Admin|null $admin
 * @property FileDisk|null $file_disk
 * @property FileType $file_type
 * @property Collection|Admin[] $admins
 * @property Collection|Advertisement[] $advertisements
 * @property Collection|Author[] $authors
 * @property Collection|BlogCategory[] $blog_categories
 * @property Collection|Blog[] $blogs
 * @property Collection|BookDocument[] $book_documents
 * @property Collection|Book[] $books
 * @property Collection|CourseCategory[] $course_categories
 * @property Collection|CourseChapter[] $course_chapters
 * @property Collection|CourseContent[] $course_contents
 * @property Collection|Course[] $courses
 * @property Collection|CourseMember[] $course_members
 * @property Collection|FaqCategory[] $faq_categories
 * @property Collection|InstructorGroup[] $instructor_groups
 * @property Collection|Instructor[] $instructors
 * @property Collection|LinkCategory[] $link_categories
 * @property Collection|Link[] $links
 * @property Collection|PageCategory[] $page_categories
 * @property Collection|Page[] $pages
 * @property Collection|Question[] $questions
 * @property Collection|SmsMethod[] $sms_methods
 * @property Collection|SocialMedia[] $social_media
 * @property Collection|SubjectContent[] $subject_contents
 * @property Collection|Subject[] $subjects
 * @property Collection|TeacherDocument[] $teacher_documents
 * @property Collection|Teacher[] $teachers
 * @property Collection|TicketAttachment[] $ticket_attachments
 * @property Collection|TicketCategory[] $ticket_categories
 * @property Collection|TicketDepartment[] $ticket_departments
 *
 * @package App\Models
 */
class File extends Model
{
	use SoftDeletes;
	protected $table = 'files';

	protected $casts = [
		'uploader_id' => 'int',
		'file_type_id' => 'int',
		'file_disk_id' => 'int',
		'size' => 'int',
		'is_private' => 'bool',
		'is_deleted' => 'bool',
		'deleted_by' => 'int'
	];

	protected $fillable = [
		'name',
		'uploader_type',
		'uploader_id',
		'file_type_id',
		'file_disk_id',
		'file_disk_name',
		'mime_type',
		'extension',
		'size',
		'path',
		'full_month',
		'original_name',
		'is_private',
		'is_deleted',
		'deleted_by'
	];

	public function admin()
	{
		return $this->belongsTo(Admin::class, 'deleted_by');
	}

	public function file_disk()
	{
		return $this->belongsTo(FileDisk::class);
	}

	public function file_type()
	{
		return $this->belongsTo(FileType::class);
	}

	public function admins()
	{
		return $this->hasMany(Admin::class, 'image_id');
	}

	public function advertisements()
	{
		return $this->hasMany(Advertisement::class, 'image_id');
	}

	public function authors()
	{
		return $this->hasMany(Author::class, 'image_id');
	}

	public function blog_categories()
	{
		return $this->hasMany(BlogCategory::class, 'header_id');
	}

	public function blogs()
	{
		return $this->belongsToMany(Blog::class, 'blog_files')
					->withPivot('id', 'title', 'description', 'download_count', 'is_lock', 'sort', 'is_active', 'created_by', 'updated_by', 'is_deleted', 'deleted_at', 'deleted_by')
					->withTimestamps();
	}

	public function book_documents()
	{
		return $this->hasMany(BookDocument::class);
	}

	public function books()
	{
		return $this->hasMany(Book::class, 'image_id');
	}

	public function course_categories()
	{
		return $this->hasMany(CourseCategory::class, 'header_id');
	}

	public function course_chapters()
	{
		return $this->hasMany(CourseChapter::class, 'image_id');
	}

	public function course_contents()
	{
		return $this->hasMany(CourseContent::class, 'header_id');
	}

	public function courses()
	{
		return $this->hasMany(Course::class, 'introduction_video_file_id');
	}

	public function course_members()
	{
		return $this->hasMany(CourseMember::class, 'certificate_file_id');
	}

	public function faq_categories()
	{
		return $this->hasMany(FaqCategory::class, 'header_id');
	}

	public function instructor_groups()
	{
		return $this->hasMany(InstructorGroup::class, 'image_id');
	}

	public function instructors()
	{
		return $this->hasMany(Instructor::class, 'image_id');
	}

	public function link_categories()
	{
		return $this->hasMany(LinkCategory::class, 'image_id');
	}

	public function links()
	{
		return $this->hasMany(Link::class, 'image_id');
	}

	public function page_categories()
	{
		return $this->hasMany(PageCategory::class, 'header_id');
	}

	public function pages()
	{
		return $this->hasMany(Page::class, 'video_link_id');
	}

	public function questions()
	{
		return $this->belongsToMany(Question::class, 'question_files')
					->withPivot('id', 'title', 'is_active', 'created_by', 'updated_by', 'is_deleted', 'deleted_at', 'deleted_by')
					->withTimestamps();
	}

	public function sms_methods()
	{
		return $this->hasMany(SmsMethod::class, 'image_id');
	}

	public function social_media()
	{
		return $this->hasMany(SocialMedia::class, 'icon_id');
	}

	public function subject_contents()
	{
		return $this->hasMany(SubjectContent::class, 'file');
	}

	public function subjects()
	{
		return $this->hasMany(Subject::class, 'image');
	}

	public function teacher_documents()
	{
		return $this->hasMany(TeacherDocument::class);
	}

	public function teachers()
	{
		return $this->hasMany(Teacher::class, 'avatar_id');
	}

	public function ticket_attachments()
	{
		return $this->hasMany(TicketAttachment::class);
	}

	public function ticket_categories()
	{
		return $this->hasMany(TicketCategory::class, 'image_id');
	}

	public function ticket_departments()
	{
		return $this->hasMany(TicketDepartment::class, 'image_id');
	}
}
