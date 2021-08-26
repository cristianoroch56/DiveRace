import React, { useEffect , useState} from "react";
import Loaderdiv from "./Loader";
import BackNextButton from "./BackNextButton";
import Summary from "../Summary/index";
import { fetchRentalEquipment ,fetchCourses,setStep,updateCourseTotalPrice, updateForm ,updateAmount, updateCourse ,updateCourseSummary ,updateCoursePrices , updateRentalEquipment, updateRentalEquipmentSummary, setPassenger} from"../../store/actions/StepWizardAction"
import { useDispatch, useSelector } from "react-redux";


const SelectAddOns = () => {

  const redux_state = useSelector((state) => state.StepWizardReducer);
  //List-selector
  const courses_list = useSelector((state) => state.StepWizardReducer.courses_list);
  const rental_equipment_list = useSelector((state) => state.StepWizardReducer.rental_equipment_list);
  
  //Course-Selector
  const courses_ids = useSelector((state) => state.StepWizardReducer.courses_types);
  const courses_price = useSelector((state) => state.StepWizardReducer.courses_price);
  const courses_sumary = useSelector((state) => state.SummaryReducer.courses_types);

  //Rental-Equipment-selector
  const rental_equipment_ids = useSelector((state) => state.StepWizardReducer.rental_equipment_types);
  const rental_equipment_sumary = useSelector((state) => state.SummaryReducer.rental_equipment_types);

  //Summary-Selector
  const summaryData = useSelector((state) => state.SummaryReducer);
  
  const [course_price, setCourse_price] = useState(0)
  const [values, setValues] = useState({ 
    courses: courses_ids,
    courses_titles:courses_sumary,
    rentalEquipments:rental_equipment_ids,
    rentalEquipments_titles: rental_equipment_sumary,
    coursesPrice:courses_price

  });

  //skipnext 
  const skipnext = (e) => {
    e.preventDefault();
    dispatch(setStep(5));
  }

  //add pax to course
  const incrementPaxCourse = (e) => {
    e.preventDefault();
    let courseId = Number(e.currentTarget.getAttribute("data-course"));
    let courseTitle = e.currentTarget.getAttribute("data-coursetitle");
    let coursePrice = Number(e.currentTarget.getAttribute("data-courseprice"));
  
    var coursesCategory = [...values.courses];
    let coursesCheckIndex = coursesCategory.findIndex(x => x.id === Number(courseId));

    if (coursesCheckIndex === -1) {
      const inPerson = 1;
      var coursesCategory =  [...values.courses , {'id':Number(courseId),'person':inPerson,'title':courseTitle,'price':coursePrice }];
    } else {
      if (redux_state.passenger == coursesCategory[coursesCheckIndex].person) {
        return;
      }
      coursesCategory[coursesCheckIndex].person += 1;
    }
    
    
    setValues({...values,courses:coursesCategory });
    dispatch(updateCourse(coursesCategory));

  }
   //remove pax to course 
  const decrementPaxCourse = (e) => {
    e.preventDefault();
    let courseId = Number(e.currentTarget.getAttribute("data-course"));

    var coursesCategory = [...values.courses];
    let coursesCheckIndex = coursesCategory.findIndex(x => x.id === Number(courseId));

    if (coursesCheckIndex === -1) {
      return;
    } else {

        if (coursesCategory[coursesCheckIndex].person === 1) {
          coursesCategory.splice(coursesCheckIndex,1);
        } else {
          coursesCategory[coursesCheckIndex].person -= 1;
        }
      
    }

    setValues({...values,courses:coursesCategory });
    dispatch(updateCourse(coursesCategory));


  }
  //add pax to equipment
  const incrementPaxEquipment = (e) => {    
    e.preventDefault();
    let equipmentId = Number(e.currentTarget.getAttribute("data-rentalequipments"));
    let rentalEquipmentTitle = e.currentTarget.getAttribute("data-rentalequipmentstitle");
    let rentalEquipmentPrice = Number(e.currentTarget.getAttribute("data-rentalequipmentsprice"));
   
    var equipmentCategory = [...values.rentalEquipments];
    let equipmentCheckIndex = equipmentCategory.findIndex(x => x.id === Number(equipmentId));

    if (equipmentCheckIndex === -1) {
      const inPerson = 1;
      var equipmentCategory =  [...values.rentalEquipments , {'id':Number(equipmentId),'person':inPerson,'title':rentalEquipmentTitle,'price':rentalEquipmentPrice }];
    } else {

      if (redux_state.passenger == equipmentCategory[equipmentCheckIndex].person) {
      
        return;
      }
      equipmentCategory[equipmentCheckIndex].person += 1;
    }
    
    setValues({...values,rentalEquipments:equipmentCategory });
    dispatch(updateRentalEquipment(equipmentCategory));
  }
  //remove pax to equipment
  const decrementPaxEquipment = (e) => {
    e.preventDefault();

    let equipmentId = Number(e.currentTarget.getAttribute("data-rentalequipments"));
    var equipmentCategory = [...values.rentalEquipments];
    let equipmentCheckIndex = equipmentCategory.findIndex(x => x.id === Number(equipmentId));

    if (equipmentCheckIndex === -1) {
      return;
    } else {

      if (equipmentCategory[equipmentCheckIndex].person === 1) {
        equipmentCategory.splice(equipmentCheckIndex,1);
      } else {
        equipmentCategory[equipmentCheckIndex].person -= 1;
      }
      
    }

    setValues({...values,rentalEquipments:equipmentCategory });
    dispatch(updateRentalEquipment(equipmentCategory));
  }
  

  const dispatch = useDispatch();
  
  useEffect(()=>{

    let addons_total_price = 0;
    for (let i = 0; i < courses_ids.length; i++) {
      addons_total_price = addons_total_price + ( Number(courses_ids[i].person)  * Number(courses_ids[i].price) ) ;
    }

    //renatl-equipments-sum
    for (let i = 0; i < rental_equipment_ids.length; i++) {
      addons_total_price = addons_total_price + ( Number(rental_equipment_ids[i].person)  * Number(rental_equipment_ids[i].price) ) ;
    }
   
    let final_amount = Number(redux_state.step3_price) +  addons_total_price;
    dispatch(updateAmount(final_amount))
    
  },[courses_ids,rental_equipment_ids]);

  useEffect(() => {

    window.scrollTo(0, 0);
    // if (rental_equipment_list.length === 0) {
      dispatch(fetchRentalEquipment())
    // }
    // if (courses_list.length === 0) {
      dispatch(fetchCourses())
    // }
   
    let passenger = redux_state.pax.length;
    
    let total_cabin_price = 0;
    for (let i = 0; i < redux_state.cabin_types.length; i++) {
      total_cabin_price = redux_state.cabin_types[i].type == 'solo' ? ( total_cabin_price + Number(redux_state.cabin_types[i].price) ) : (redux_state.cabin_types[i].seat === 'both' ? (total_cabin_price + Number(redux_state.cabin_types[i].price) ) : (total_cabin_price + Number(redux_state.cabin_types[i].price )/2 )); 
    }
    
    const step3_price = total_cabin_price;


    dispatch(setPassenger(Number(passenger)))  // set no of pax
    dispatch(updateForm('step3_price',step3_price))   // pax * cabin_price

  
    // let ste4_price = redux_state.total_course_price > 0.00 ? Number(redux_state.total_course_price) * Number(passenger) : 0;
    var ste4_total_price = 0;
    if (courses_ids.length > 0 || rental_equipment_ids.length > 0 ) {

      
      for (let i = 0; i < courses_ids.length; i++) {
        ste4_total_price = ste4_total_price + ( Number(courses_ids[i].person)  * Number(courses_ids[i].price) ) ;
      }
  
      //renatl-equipments-sum
      for (let i = 0; i < rental_equipment_ids.length; i++) {
        ste4_total_price = ste4_total_price + ( Number(rental_equipment_ids[i].person)  * Number(rental_equipment_ids[i].price) ) ;
      }
    }
    
    let upto_step3_price = step3_price + Number(ste4_total_price);
     
    dispatch(updateAmount(upto_step3_price))

    
  }, []);
  
  return (
    <React.Fragment>
      <div id="step-4" className="container content-center step-main-wrapper addons-wrapper" style={{display:'block'}}>

      {/* { rental_equipment_list.length === 0 && <Loaderdiv /> } */}
      
        <div className="row">
          <div className="col-12 col-sm-6 col-md-7">
              <h4 className="heading display-course-title">Select Any Course</h4>
          </div>
          <div className="col-12 col-sm-6 col-md-5">
            <button type="button" className="btn btn-primary btn-lg btn-addons-skip" onClick={skipnext}>Skip to Next Step</button>
          </div>
        </div>
        <div className="row">
          <div className="body-left col-md-7">
            <div className="select-area">
              <h4 className="heading hidden-course-title">Select Any Course</h4>
              {/* <h5 className="text-dark">Note: You can purchase Course or Rental-equipment maximum {redux_state.passenger} per pax</h5> */}
              <h5 className="text-dark course-note">Note: You can also book courses and equipments on edit booking page.</h5>
              <section className="col-sm-12 col-md-10">
                 <div className="row date-area border-rd date-wrapper-section course-wrapper">                      
                      {courses_list.length === 0 ? (<div className="text-center w-100">NO RECORD FOUND</div>) : courses_list.map((course_obj, c)=>{
                        return (
                          <div key={c} className="border-right col-12 col-sm-6 col-md-6">
                            <div 
                              className={`date-box course-box`}
                      
                            >
                              <div className="col-2 col-sm-3 col-md-3 img-section">
                                <img
                                  src={
                                    process.env.PUBLIC_URL + "/assets/ic_course.png"
                                  }
                                  alt="course"
                                />
                              </div>
                              <div className="col-10 col-sm-9 col-md-9 text-left">
                              <p>{course_obj.title}</p>
                              {/* <a href="#" > {course_obj.course_price}SGD per pax </a> */}
                                <span className="summary-text-info"> {course_obj.course_price}SGD per pax </span>
                                <div className="manage-course pt-2">
                                  <img
                                    src={process.env.PUBLIC_URL + "/assets/circle-plus.svg"} alt="CirclePlus Icon" className="circle-plus-icon pointer" aria-hidden="true"  data-course={course_obj.id} data-coursetitle={course_obj.title} data-courseprice={course_obj.course_price}  data-title={`${course_obj.course_price}SGD per pax`}  onClick={(e)=>incrementPaxCourse(e)}
                                   />                                  
                                    <input type="text" readOnly className="total-course-seleted" value={courses_ids.find(item => item.id === course_obj.id ) === undefined ? 0 : courses_ids.find(item => item.id === course_obj.id ).person } />

                                  <img
                                    src={process.env.PUBLIC_URL + "/assets/circle-minus.svg"} alt="CircleMinus Icon" className="circle-minus-icon pointer" aria-hidden="true"  data-course={course_obj.id} data-coursetitle={course_obj.title} data-courseprice={course_obj.course_price}  data-title ={`${course_obj.course_price}SGD per pax`}  onClick={decrementPaxCourse} 
                                   />   
                                  
                                </div>

  
                              </div>

                          </div> 
                        </div>
                        )
                      })}
                </div>
                <div className="select row border-rd">
                  <span className="p-3 col-md-12 fs-18">
                    {/* <i className="fa fa-check" aria-hidden="true"></i>  */}
                    {redux_state.courses_types.length > 0 ? redux_state.courses_types.map((course_pbj,i) => {
                      return (
                        <React.Fragment key={i}>
                          {`${course_pbj.title} x ${course_pbj.person}`} 
                          <br/>
                        </React.Fragment>
                      )
                    }) : "No course selected"}
                    {/* { summaryData.courses_types.length === 0 ? "No course selected" : summaryData.courses_types.map((summ,z) => { return (z ? ', ' : '') + summ  })} */}
                  </span>
                </div>
              </section>
            </div>

            <div className="select-area row row-equipment">
              <div className="col-sm-12 col-md-12">
                <h4 className="heading">Select Rental Equipment</h4>
              </div>                              
              <div className="col-sm-12 col-md-4 date-link">
                <div className="select-date">
                  {/* <a href="#">
                    {" "}
                    <i className="fa fa-compress" aria-hidden="true"></i> Sizing
                    Guide
                  </a> */}
                </div>
              </div>

              <section className="p-3 col-sm-12 col-md-10">
              <div className="row date-area border-rd date-wrapper-section equipment-wrapper">                 
                      {rental_equipment_list.length === 0 ? (<div className="text-center w-100">NO RECORD FOUND</div>) : rental_equipment_list.map((rental_obj,r)=> {
                        return (
                          <div key={r} className="border-right col-12 col-sm-6 col-md-6">
                              <div 
                                className={`date-box course-box`} 
                                
                              
                              >
                                <div className="col-2 col-sm-3 col-md-3 img-equipment">
                                  <img
                                    src={
                                      process.env.PUBLIC_URL + "/assets/ic_diving.png"
                                    }
                                    alt="diving"
                                  />
                                </div>
                                <div className="col-9 col-sm-8 col-md-8 text-left">
                                  <p>{rental_obj.title}</p>
                                <span >{rental_obj.rental_equipment_term}</span>
                                <span className="summary-text-info"> {rental_obj.rental_equipment_price}SGD per pax </span>
                                <div className="manage-course pt-2">
                                  <img src={process.env.PUBLIC_URL + "/assets/circle-plus.svg"} alt="CirclePlus Icon" className="circle-plus-icon pointer" aria-hidden="true"  data-rentalequipments={rental_obj.id} data-rentalequipmentstitle={rental_obj.title} data-rentalequipmentsprice={rental_obj.rental_equipment_price} onClick={(e)=>incrementPaxEquipment(e)} 
                                  />
                                    <input type="text" readOnly className="total-course-seleted" value={rental_equipment_ids.find(item => item.id === rental_obj.id ) === undefined ? 0 : rental_equipment_ids.find(item => item.id === rental_obj.id ).person } />
                                  <img src={process.env.PUBLIC_URL + "/assets/circle-minus.svg"} alt="CircleMinus Icon" className="circle-minus-icon pointer" aria-hidden="true"  data-rentalequipments={rental_obj.id}  data-rentalequipmentstitle={rental_obj.title} data-rentalequipmentsprice={rental_obj.rental_equipment_price} onClick={(e)=>decrementPaxEquipment(e)}
                                  />
                                </div>
                                
                                </div>
                                <div className="guide-ic col-1 col-sm-1 col-md-1 text-left">
                                  <a href="#">
                                    {" "}
                                    <i
                                      className="fa fa-angle-right"
                                      aria-hidden="true"
                                    ></i>{" "}
                                  </a>
                                </div>
                              </div>
                            </div>
                        )
                      })}
                    
                </div>
                <div className="select row border-rd msg-wrapper-equipment">
                  <span className="col-md-12 fs-18">
                    {/* <i className="fa fa-check" aria-hidden="true"></i> */}
                    {redux_state.rental_equipment_types.length > 0 ? redux_state.rental_equipment_types.map((rental_pbj,i) => {
                      return (
                        <React.Fragment key={i}>
                          {`${rental_pbj.title} x ${rental_pbj.person}`} 
                          <br/>
                        </React.Fragment>
                      )
                    }) : "No Rental equipment selected"}
                    {/* { summaryData.rental_equipment_types.length === 0 ? "No Rental equipment selected" : summaryData.rental_equipment_types.map((rental,r) => { return (r ? ', ' : '') + rental  })} */}
                    {" "}
                  </span>
                </div>
              </section>

            </div>
          </div>
          

          {/* Summary */}
            {/* summary-wrappper */}
            <Summary step={4}></Summary>
          {/* Summary */}
          
        </div>
      </div>
      <div
        className="bottom-text pt-4"
        style={{
          display: "block",
          margin: "inherit",
          textAlign: "center",
        }}
      ></div>
      <BackNextButton
        back={3}
        next={5}
        label={`Proceed to Overview`}
        disabled={false}
      ></BackNextButton>
    </React.Fragment>
  );
};

export default SelectAddOns;
