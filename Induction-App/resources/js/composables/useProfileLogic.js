import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

export function useProfileLogic(userDetails, qualificationData, ageRelaxation, provinces) {
  const photoPreview = ref(null);
  const fileInput = ref(null);
  const qualificationForm = ref({ id: null, degree: '', field: '', institution: '', year: '' });
  const qualificationFormErrors = ref({});
  const editingQualification = ref(false);
  const editingIndex = ref(null);

  // Initialize forms
  const form = useForm({
    user_id: userDetails?.id || '',
    name: userDetails?.name || '',
    email: userDetails?.email || '',
    fatherName: userDetails?.candidate_profile?.father_name || '',
    gender: userDetails?.candidate_profile?.gender || '',
    dateOfBirth: userDetails?.candidate_profile?.date_of_birth || '',
    city: userDetails?.candidate_profile?.city?.id || '',
    cnic: userDetails?.candidate_profile?.cnic || '',
    mobile: userDetails?.user_contact?.mobile || '',
    phone: userDetails?.user_contact?.phone || '',
    permanentAddress: userDetails?.user_contact?.postal_address || '',
    postalAddress: userDetails?.user_contact?.postal_address || '',
    disabilityStatus: userDetails?.candidate_profile?.disability || '',
    religion: userDetails?.candidate_profile?.religion || '',
    maritalStatus: userDetails?.candidate_profile?.marital_status ? 'Married' : 'Single',
    useHusbandDomicile: userDetails?.user_spouses?.length ? 'Yes' : '',
    husbandName: userDetails?.user_spouses?.spouse_name || '',
    husbandCnic: userDetails?.user_spouses?.spouse_cnic || '',
    husbandProvince: userDetails?.user_spouses?.[0]?.city?.district?.province?.id || '',
    husbandDistrict: userDetails?.user_spouses?.[0]?.city?.district?.id || '',
    husbandCity: userDetails?.user_spouses?.[0]?.city_id || '',
    province: userDetails?.candidate_profile?.city?.district?.province?.id || '',
    district: userDetails?.candidate_profile?.city?.district?.id || ''
  });

  const ageRelaxationForm = useForm({
    relax_schedule_caste: ageRelaxation?.relax_schedule_caste || false,
    relax_retired: ageRelaxation?.relax_retired || false,
    relax_retired_from: ageRelaxation?.relax_retired_from || '',
    relax_retired_position: ageRelaxation?.relax_retired_position || '',
    relax_retired_appoint: ageRelaxation?.relax_retired_appoint || '',
    relax_retired_retired: ageRelaxation?.relax_retired_retired || '',
    relax_disable: ageRelaxation?.relax_disable || false,
    relax_disabled_nature: ageRelaxation?.relax_disabled_nature || '',
    relax_widow: ageRelaxation?.relax_widow || false,
    relax_name_employ: ageRelaxation?.relax_name_employ || '',
    relax_designation: ageRelaxation?.relax_designation || '',
    relax_department: ageRelaxation?.relax_department || '',
    relax_date_death: ageRelaxation?.relax_date_death || '',
    gov: ageRelaxation?.gov || false,
    gov_name: ageRelaxation?.gov_name || '',
    gov_designation: ageRelaxation?.gov_designation || '',
    gov_basic_pay: ageRelaxation?.gov_basic_pay || '',
    gov_appoint_date: ageRelaxation?.gov_appoint_date || '',
    gov_retire_date: ageRelaxation?.gov_retire_date || '',
    gov_appoint_nature: ageRelaxation?.gov_appoint_nature || ''
  });

  // Profile methods
  const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        photoPreview.value = event.target.result;
      };
      reader.readAsDataURL(file);
      form.photo = file;
    }
  };

  const removePhoto = () => {
    photoPreview.value = null;
    fileInput.value.value = '';
    form.photo = null;
  };

  const resetForm = () => {
    form.reset();
  };

  const saveForm = () => {
    if (form.gender === 'Female' && form.maritalStatus === 'Married' && form.useHusbandDomicile === 'Yes') {
      if (!form.husbandName) form.errors.husbandName = "Husband's name is required";
      if (!form.husbandCnic || form.husbandCnic.length !== 13) form.errors.husbandCnic = "Husband's CNIC must be 13 digits";
      if (!form.husbandProvince) form.errors.husbandProvince = "Husband's province is required";
      if (!form.husbandDistrict) form.errors.husbandDistrict = "Husband's district is required";
      if (!form.husbandCity) form.errors.husbandCity = "Husband's city is required";
      if (Object.values(form.errors).some(error => error)) {
        alert('Please fill all required fields in Husband’s Details section.');
        return;
      }
    }
    if (form.cnic && form.cnic.length !== 13) {
      form.errors.cnic = 'CNIC must be 13 digits';
      alert('CNIC must be 13 digits.');
      return;
    }
    form.post(route('candidate.profile.update'), {
      preserveScroll: true,
      onSuccess: () => alert('Profile updated successfully!'),
      onError: (errors) => alert('Error updating profile:\n' + Object.values(errors).join('\n'))
    });
  };

  const handleAddressChange = (type) => async () => {
    const prefix = type === 'user' ? '' : 'husband';
    const provinceKey = `${prefix}Province`;
    const districtKey = `${prefix}District`;
    const cityKey = `${prefix}City`;
    const districtsRef = type === 'user' ? districts : husbandDistricts;
    const citiesRef = type === 'user' ? cities : husbandCities;

    if (!form[provinceKey]) {
      districtsRef.value = [];
      citiesRef.value = [];
      form[districtKey] = '';
      form[cityKey] = '';
      return;
    }
    try {
      const response = await axios.get(route('districts.fetch', { province_id: form[provinceKey] }));
      districtsRef.value = response.data.districts;
      citiesRef.value = [];
      form[districtKey] = '';
      form[cityKey] = '';
    } catch (error) {
      console.error(`Error fetching ${type} districts:`, error);
      districtsRef.value = [];
      citiesRef.value = [];
      form[districtKey] = '';
      form[cityKey] = '';
    }

    if (form[districtKey]) {
      try {
        const response = await axios.get(route('cities.fetch', { district_id: form[districtKey] }));
        citiesRef.value = response.data.cities;
        form[cityKey] = '';
      } catch (error) {
        console.error(`Error fetching ${type} cities:`, error);
        citiesRef.value = [];
        form[cityKey] = '';
      }
    }
  };

  // Qualification methods
  const validateQualificationForm = () => {
    const errors = {};
    if (!qualificationForm.value.degree) errors.degree = 'Degree is required';
    if (!qualificationForm.value.field) errors.field = 'Field of study is required';
    if (!qualificationForm.value.institution) errors.institution = 'Institution is required';
    if (!qualificationForm.value.year) {
      errors.year = 'Year of completion is required';
    } else if (qualificationForm.value.year < 1900 || qualificationForm.value.year > new Date().getFullYear()) {
      errors.year = 'Please enter a valid year';
    }
    qualificationFormErrors.value = errors;
    return Object.keys(errors).length === 0;
  };

  const saveQualification = () => {
    if (!validateQualificationForm()) return;
    const qualificationData = {
      id: qualificationForm.value.id,
      degree_type_id: qualificationForm.value.degree,
      degree_name: qualificationForm.value.field,
      institute_name: qualificationForm.value.institution,
      passing_year: qualificationForm.value.year
    };
    const form = useForm(qualificationData);
    form.post(route('candidate.qualification.store'), {
      preserveScroll: true,
      onSuccess: () => {
        const newQualification = { ...qualificationForm.value };
        if (editingQualification.value) {
          qualifications.value[editingIndex.value] = newQualification;
        } else {
          qualifications.value.push(newQualification);
        }
        qualificationForm.value = { id: null, degree: '', field: '', institution: '', year: '' };
        qualificationFormErrors.value = {};
        editingQualification.value = false;
        editingIndex.value = null;
        alert(editingQualification.value ? 'Qualification updated successfully!' : 'Qualification added successfully!');
      },
      onError: (errors) => {
        console.error('Validation errors:', errors);
        qualificationFormErrors.value = errors;
      }
    });
  };

  const editQualification = (qualification, index) => {
    qualificationForm.value = { ...qualification };
    editingQualification.value = true;
    editingIndex.value = index;
    qualificationFormErrors.value = {};
  };

  const deleteQualification = (qualification, index) => {
    if (!confirm('Are you sure you want to delete this qualification?')) return;
    if (qualification.id) {
      const form = useForm({});
      form.delete(route('candidate.qualification.destroy', qualification.id), {
        preserveScroll: true,
        onSuccess: () => {
          qualifications.value.splice(index, 1);
          alert('Qualification deleted successfully!');
        },
        onError: () => alert('Error deleting qualification!')
      });
    } else {
      qualifications.value.splice(index, 1);
    }
  };

  const getDegreeName = (degreeId) => {
    const degree = provinces.find(type => type.id == degreeId);
    return degree ? degree.name : 'Unknown';
  };

  // Age Relaxation methods
  const saveAgeRelaxationForm = () => {
    if (
      ageRelaxationForm.relax_retired &&
      (!ageRelaxationForm.relax_retired_from ||
       !ageRelaxationForm.relax_retired_position ||
       !ageRelaxationForm.relax_retired_appoint ||
       !ageRelaxationForm.relax_retired_retired)
    ) {
      alert('Please fill all required fields in Retired Armed Forces section.');
      return;
    }
    if (ageRelaxationForm.relax_disable && !ageRelaxationForm.relax_disabled_nature) {
      alert('Please select the nature of disability.');
      return;
    }
    if (
      ageRelaxationForm.relax_widow &&
      (!ageRelaxationForm.relax_name_employ ||
       !ageRelaxationForm.relax_designation ||
       !ageRelaxationForm.relax_department ||
       !ageRelaxationForm.relax_date_death)
    ) {
      alert('Please fill all required fields in Widow/Widower section.');
      return;
    }
    if (
      ageRelaxationForm.gov &&
      (!ageRelaxationForm.gov_name ||
       !ageRelaxationForm.gov_designation ||
       !ageRelaxationForm.gov_basic_pay ||
       !ageRelaxationForm.gov_appoint_date ||
       !ageRelaxationForm.gov_appoint_nature)
    ) {
      alert('Please fill all required fields in Government Employee section.');
      return;
    }
    ageRelaxationForm.post(route('candidate.ageRelaxation.update'), {
      preserveScroll: true,
      onSuccess: () => alert('Age Relaxation updated successfully!'),
      onError: (errors) => alert('Error updating age relaxation:\n' + Object.values(errors).join('\n'))
    });
  };

  const resetAgeRelaxationForm = () => {
    ageRelaxationForm.reset();
  };

  return {
    form,
    ageRelaxationForm,
    qualificationForm,
    qualificationFormErrors,
    editingQualification,
    editingIndex,
    photoPreview,
    fileInput,
    handleFileUpload,
    removePhoto,
    resetForm,
    saveForm,
    handleAddressChange,
    saveQualification,
    editQualification,
    deleteQualification,
    saveAgeRelaxationForm,
    resetAgeRelaxationForm,
    getDegreeName
  };
}