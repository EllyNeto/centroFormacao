/* Datalist auto-completion for Enrollment Create and Edit forms */
document.addEventListener('DOMContentLoaded', function() {
    const studentSearchInput = document.getElementById('student_search');
    const studentHiddenInput = document.getElementById('student_id');
    const studentsDatalist   = document.getElementById('students_list');

    if (studentSearchInput && studentHiddenInput && studentsDatalist) {
        studentSearchInput.addEventListener('input', function() {
            const val = this.value;
            const options = studentsDatalist.options;
            studentHiddenInput.value = '';
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    studentHiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });
    }

    const courseSearchInput = document.getElementById('course_search');
    const courseHiddenInput = document.getElementById('course_id');
    const coursesDatalist   = document.getElementById('courses_list');

    if (courseSearchInput && courseHiddenInput && coursesDatalist) {
        courseSearchInput.addEventListener('input', function() {
            const val = this.value;
            const options = coursesDatalist.options;
            courseHiddenInput.value = '';
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    courseHiddenInput.value = options[i].getAttribute('data-id');
                    break;
                }
            }
        });
    }
});
