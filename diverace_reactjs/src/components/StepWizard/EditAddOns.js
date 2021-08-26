import React from "react";
import Header from "../Header";
import Footer from "../Footer";
import PaymentCheckout from "../PaymentCheckout";
import OrderBookedSummary from "../Summary/OrderBookedSummary"; 
import {
  fetchBookedRentalEquipment,
  fetchBookedCourses,updateForm,getBookedCourses
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";

const EditAddOns = (props) => {

  const { match: { params } } = props;
 
  const courses_list = useSelector((state) => state.StepWizardReducer.order_booked_courses);
  const rental_equipment_list = useSelector((state) => state.StepWizardReducer.order_booked_equipments);
  const summaryData = useSelector((state) => state.StepWizardReducer.order_booked_summary);
 
  const userLoginData = useSelector((state) => state.user.profile);

  var js_courses = JSON.parse(window.localStorage.getItem('prev_courses_data'));
  var js_rental = JSON.parse(window.localStorage.getItem('prev_rental_data'));

  // const prevent_courses = useSelector((state) => state.StepWizardReducer.prevent_courses_one);
  // const prevent_rental_equipment = useSelector((state) => state.StepWizardReducer.prevent_rental_equipment);

  const [courses_ids, setCourses_ids] = React.useState([]);
  const [rental_equipment_ids, setRental_equipment_ids] = React.useState([]);

  const [orderId , setOrderId] = React.useState(params.order_id);
  

  //add pax to course
  const incrementPaxCourse = (e) => {
    e.preventDefault();

    let courseId = Number(e.currentTarget.getAttribute("data-course"));
    let coursePrice = Number(e.currentTarget.getAttribute("data-courseprice"));

    var coursesCategory = [...courses_list];
    let coursesCheckIndex = coursesCategory.findIndex(x => x.id === Number(courseId));
   
    if (summaryData.total_person == coursesCategory[coursesCheckIndex].booked_course) {
      
      return;
    }

    //-------------local state
    var localCoursesCategory = [...courses_ids];
    let localCoursesCheckIndex = localCoursesCategory.findIndex(x => x.id === Number(courseId));
    if (localCoursesCheckIndex === -1) {
      const inPerson = 1;
      var localCoursesCategory =  [...courses_ids , {'id':Number(courseId),'person':inPerson,'price':coursePrice }];
    } else {
      localCoursesCategory[localCoursesCheckIndex].person += 1;
    }
    setCourses_ids(localCoursesCategory);
    //-----------------

    coursesCategory[coursesCheckIndex].booked_course += 1;
    dispatch(updateForm('order_booked_courses', coursesCategory));
    
  
  };

  //remove pax to course
  const decrementPaxCourse = (e) => {
    e.preventDefault();


    let courseId = Number(e.currentTarget.getAttribute("data-course"));
    let bookedcourse = Number(e.currentTarget.getAttribute("data-bookedcourse"));
    
    //check existing courses
    // let existingCheckCourseIndex = prevent_courses.findIndex(x => x.id === Number(courseId));
    let existingCheckCourseIndex = js_courses.findIndex(x => x.id === Number(courseId));
   
    if (js_courses[existingCheckCourseIndex].booked_course >= Number(bookedcourse)) {
      
      return;
    }

    //----------------local state
    var localcoursesCategory = [...courses_ids];
    let localcoursesCheckIndex = localcoursesCategory.findIndex(x => x.id === Number(courseId));

    if (localcoursesCheckIndex === -1) {
      return;
    } else {

        if (localcoursesCategory[localcoursesCheckIndex].person === 1) {
          localcoursesCategory.splice(localcoursesCheckIndex,1);
        } else {
          localcoursesCategory[localcoursesCheckIndex].person -= 1;
        }
        setCourses_ids(localcoursesCategory);
    }
    //--------------------------

    var coursesCategory = [...courses_list];
    let coursesCheckIndex = coursesCategory.findIndex(x => x.id === Number(courseId));
    
    if (coursesCheckIndex === -1) {
      return;
    } else {

        if (coursesCategory[coursesCheckIndex].booked_course === 1) {
          coursesCategory[coursesCheckIndex].booked_course -= 1
          // coursesCategory.splice(coursesCheckIndex,1);
        } else {
          coursesCategory[coursesCheckIndex].booked_course -= 1;
        }
        dispatch(updateForm('order_booked_courses', coursesCategory));
    }

    
  };

  //add pax to equipment
  const incrementPaxEquipment = (e) => {
      e.preventDefault();

  
      let equipmentId = Number(e.currentTarget.getAttribute("data-rentalequipments"));
      let rentalEquipmentPrice = Number( e.currentTarget.getAttribute("data-rentalequipmentsprice"));

      var equipmentCategory = [...rental_equipment_list];
      let equipmentCheckIndex = equipmentCategory.findIndex(x => x.id === Number(equipmentId));


      if (summaryData.total_person == equipmentCategory[equipmentCheckIndex].booked_rental_equipment) {
        
        return;
      }

      //----------local rental-equipments
      var localRentalCategory = [...rental_equipment_ids];
      let localRentalCheckIndex = localRentalCategory.findIndex(x => x.id === Number(equipmentId));
      if (localRentalCheckIndex === -1) {
        const inPerson = 1;
        var localRentalCategory =  [...rental_equipment_ids , {'id':Number(equipmentId),'person':inPerson,'price':rentalEquipmentPrice }];
      } else {
        localRentalCategory[localRentalCheckIndex].person += 1;
      }
      setRental_equipment_ids(localRentalCategory);
      //-------------------------------
      equipmentCategory[equipmentCheckIndex].booked_rental_equipment += 1;
      dispatch(updateForm('order_booked_equipments',equipmentCategory))


  };

  //remove pax to equipment
  const decrementPaxEquipment = (e) => {
    e.preventDefault();


    let equipmentId = Number(e.currentTarget.getAttribute("data-rentalequipments"));
    let  bookedrentalequipments  = Number(e.currentTarget.getAttribute("data-bookedrentalequipments"));
    
    //check validation
    let existingCheckRentalIndex = js_rental.findIndex(x => x.id === Number(equipmentId));
    if (js_rental[existingCheckRentalIndex].booked_rental_equipment >= Number(bookedrentalequipments)) {
      
      return;
    }
    //--------------


    //----------------local state
    var localrentalCategory = [...rental_equipment_ids];
    let localrentalCheckIndex = localrentalCategory.findIndex(x => x.id === Number(equipmentId));

    if (localrentalCheckIndex === -1) {
      return;
    } else {

        if (localrentalCategory[localrentalCheckIndex].person === 1) {
          localrentalCategory.splice(localrentalCheckIndex,1);
        } else {
          localrentalCategory[localrentalCheckIndex].person -= 1;
        }
        setRental_equipment_ids(localrentalCategory);
    }
    //--------------------------

    var equipmentCategory = [...rental_equipment_list];
    let equipmentCheckIndex = equipmentCategory.findIndex(x => x.id === Number(equipmentId));


    if (equipmentCheckIndex === -1) {
      return;
    } else {

      if (equipmentCategory[equipmentCheckIndex].booked_rental_equipment === 1) {
        equipmentCategory[equipmentCheckIndex].booked_rental_equipment -= 1
        //equipmentCategory.splice(equipmentCheckIndex,1);
      } else {
        equipmentCategory[equipmentCheckIndex].booked_rental_equipment -= 1;
      }
      dispatch(updateForm('order_booked_equipments', equipmentCategory));
    }

  };

  const dispatch = useDispatch();

  React.useEffect(() => {
    
    window.scrollTo(0, 0);
    document.body.style.background = `#ffffff`;
    //const ORDER_ID = Number(params.order_id);
    // const ORDER_ID = params.id;
    const ORDER_ID = orderId;
    const USER_ID = userLoginData.ID;
   
    dispatch(fetchBookedCourses(ORDER_ID, USER_ID));
    dispatch(fetchBookedRentalEquipment(ORDER_ID, USER_ID));
  
  }, []);

  React.useEffect(() => {
 
      let addons_total_price = 0;
      for (let i = 0; i < courses_ids.length; i++) {
        addons_total_price = addons_total_price + ( Number(courses_ids[i].person)  * Number(courses_ids[i].price) ) ;
      }

      //renatl-equipments-sum
      for (let i = 0; i < rental_equipment_ids.length; i++) {
        addons_total_price = addons_total_price + ( Number(rental_equipment_ids[i].person)  * Number(rental_equipment_ids[i].price) ) ;
      }
     
      dispatch(updateForm('update_addons_amount',Number(addons_total_price)));
      dispatch(updateForm('updated_final_amount',Number(addons_total_price)));
      //------------------------- If user has credit ---------------------------------//
      if (summaryData && Number(summaryData.user_credit) > 0) {

        if (Number(summaryData.user_credit) >= Number(addons_total_price) ) {
          
          var user_credit = addons_total_price;
          var diduct_amount = 0.00;

          dispatch(updateForm('final_pay_amount',Number(user_credit)));

        } else {

          var user_credit = summaryData.user_credit;
          var diduct_amount = Number(addons_total_price) - Number(summaryData.user_credit);
        }

        dispatch(updateForm('user_credit',Number(user_credit)));
        dispatch(updateForm('updated_final_amount',Number(diduct_amount)));
      }

      /* let updated_final_amount =  addons_total_price;
      dispatch(updateForm('updated_final_amount',Number(updated_final_amount))) */

  },[courses_ids,rental_equipment_ids]);

  return (
    <React.Fragment>
      {/* Header */}
      <Header></Header>
      {/* Header */}
      <div className="top">
        <div className="area">
          <div className="main-wrapper-section">
            <div className="container-fluid" id="smartwizard">
              {/* Stepper */}

              {/* Stepper */}

              <div className="pt-4 main-bg-clr container-fluid main-step-section-contents">
                {/* Form */}
                <div
                  id="step-4"
                  className="pt-4 container content-center addons-wrapper"
                  style={{ display: "block" }}
                >
                  <div className="row">
                    <div className="col-md-7 pb-15">
                      <h4 className="heading display-course-title">
                        Select Any Course
                      </h4>
                    </div>
                    {/* <div className="col-md-5">
                      <Link to="/your_orders">
                      <button
                        type="button"
                        className="btn btn-primary btn-lg btn-addons-skip"
                        
                      >
                        BACK TO HOME
                      </button>
                      </Link>
                    </div> */}
                  </div>
                  <div className="row">
                    <div className="body-left col-md-7">
                      <div className="select-area">
                        <h4 className="heading hidden-course-title">
                          Select Any Course
                        </h4>
                        <section className="col-sm-12 col-md-10">
                          <div className="row date-area border-rd date-wrapper-section course-wrapper">
                            {courses_list.length === 0 ? (
                              <div className="text-center w-100">
                                NO RECORD FOUND
                              </div>
                            ) : (
                              courses_list.map((course_obj, c) => {
                                return (
                                  <div
                                    key={c}
                                    className="border-right col-12 col-sm-6 col-md-6"
                                  >
                                    <div className={`date-box course-box`}>
                                      <div className="col-2 col-sm-3 col-md-3 img-section">
                                        <img
                                          src={
                                            process.env.PUBLIC_URL +
                                            "/assets/ic_course.png"
                                          }
                                          alt="course"
                                        />
                                      </div>
                                      <div className="col-10 col-sm-9 col-md-9 text-left">
                                        <p>{course_obj.title}</p>
                                        {/* <a href="#" > {course_obj.course_price}SGD per pax </a> */}
                                        <span className="summary-text-info">
                                          {" "}
                                          {course_obj.course_price}SGD per pax{" "}
                                        </span>
                                        <div className="manage-course text-center pt-2">
                                          <i
                                            className="fa fa-plus pointer"
                                            aria-hidden="true"
                                            data-course={course_obj.id}
                                          
                                            data-coursetitle={course_obj.title}
                                            data-courseprice={
                                              course_obj.course_price
                                            }
                                            data-title={`${course_obj.course_price}SGD per pax`}
                                            onClick={(e) =>
                                              incrementPaxCourse(e)
                                            }
                                          ></i>
                                          <input
                                            type="text"
                                            readOnly
                                            className="total-course-seleted"
                                            value={
                                              course_obj.booked_course
                                            }
                                          />
                                          <i
                                            className="fa fa-minus pointer"
                                            aria-hidden="true"
                                            data-course={course_obj.id}
                                            data-bookedcourse={course_obj.booked_course}
                                            data-coursetitle={course_obj.title}
                                            data-courseprice={
                                              course_obj.course_price
                                            }
                                            data-title={`${course_obj.course_price}SGD per pax`}
                                            onClick={(e) => decrementPaxCourse(e)}
                                          ></i>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                );
                              })
                            )}
                          </div>
                          <div className="select row border-rd">
                            <span className="p-3 col-md-12">
                              {/* <i className="fa fa-check" aria-hidden="true"></i>  */}
                              {courses_list.length > 0 ? courses_list.map((course_pbj,i) => {
                                return (
                                  <React.Fragment key={i}>
                                    
                                    {course_pbj.booked_course !== 0 && <div>{`${course_pbj.title} x ${course_pbj.booked_course}`} <br/></div>} 
                                   
                                  </React.Fragment>
                                )
                              }) : "No course selected"}
                              
                            </span>
                          </div>
                        </section>
                      </div>

                      <div className="select-area row row-equipment">
                        <div className="col-sm-12 col-md-6">
                          <h4 className="heading">Select Rental Equipment</h4>
                        </div>
                        <div className="col-sm-12 col-md-4 date-link">
                          <div className="select-date">
                            <a href="#">
                              {" "}
                              <i
                                className="fa fa-compress"
                                aria-hidden="true"
                              ></i>{" "}
                              Sizing Guide
                            </a>
                          </div>
                        </div>

                        <section className="p-3 col-sm-12 col-md-10">
                          <div className="row date-area border-rd date-wrapper-section equipment-wrapper">
                            {rental_equipment_list.length === 0 ? (
                              <div className="text-center w-100">
                                NO RECORD FOUND
                              </div>
                            ) : (
                              rental_equipment_list.map((rental_obj, r) => {
                                return (
                                  <div
                                    key={r}
                                    className="border-right col-12 col-sm-6 col-md-6"
                                  >
                                    <div className={`date-box course-box`}>
                                      <div className="col-2 col-sm-3 col-md-3 img-equipment">
                                        <img
                                          src={
                                            process.env.PUBLIC_URL +
                                            "/assets/ic_diving.png"
                                          }
                                          alt="diving"
                                        />
                                      </div>
                                      <div className="col-9 col-sm-8 col-md-8 text-left">
                                        <p>{rental_obj.title}</p>
                                        <span>
                                          {rental_obj.rental_equipment_term}
                                        </span>
                                        <span className="summary-text-info">
                                          {" "}
                                          {rental_obj.rental_equipment_price}SGD
                                          per pax{" "}
                                        </span>
                                        <div className="manage-course text-center pt-2">
                                          <i
                                            className="fa fa-plus pointer"
                                            aria-hidden="true"
                                            data-rentalequipments={ rental_obj.id }
                                           
                                            data-rentalequipmentstitle={
                                              rental_obj.title
                                            }
                                            data-rentalequipmentsprice={
                                              rental_obj.rental_equipment_price
                                            }
                                            onClick={(e) =>
                                              incrementPaxEquipment(e)
                                            }
                                          ></i>
                                          <input
                                            type="text"
                                            readOnly
                                            className="total-course-seleted"
                                            value={
                                              rental_obj.booked_rental_equipment
                                            }
                                          />
                                          <i
                                            className="fa fa-minus pointer"
                                            aria-hidden="true"
                                            data-rentalequipments={
                                              rental_obj.id
                                            }
                                            data-bookedrentalequipments={ rental_obj.booked_rental_equipment }
                                            data-rentalequipmentstitle={
                                              rental_obj.title
                                            }
                                            data-rentalequipmentsprice={
                                              rental_obj.rental_equipment_price
                                            }
                                            onClick={(e) =>
                                              decrementPaxEquipment(e)
                                            }
                                          ></i>
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
                                );
                              })
                            )}
                          </div>
                          <div className="select row border-rd msg-wrapper-equipment">
                            <span className="col-md-12">
                              {/* <i className="fa fa-check" aria-hidden="true"></i> */}
                              {rental_equipment_list.length > 0 ? rental_equipment_list.map((rental_pbj,i) => {
                                return (
                                  <React.Fragment key={i}>
                              
                                    {rental_pbj.booked_rental_equipment !==0 && <div>{`${rental_pbj.title} x ${rental_pbj.booked_rental_equipment}`} <br/> </div>} 
                                   
                                  </React.Fragment>
                                )
                              }) : "No Rental equipment selected"}
                              
                            </span>
                          </div>
                        </section>
                      </div>
                    </div>

                    {/* Summary */}
                    {/* summary-wrappper */}
                    <OrderBookedSummary order_id={Number(orderId)} user_id={Number(userLoginData.ID)}></OrderBookedSummary>
                    {/* <Summary step={4}></Summary> */}
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
                <div className="container" style={{paddingBottom:30, display:"block",textAlign:"center",margin:"0 auto"}}>
                <div className="row button-section-wrapper pl-15" >
                    <PaymentCheckout/>
                </div>
                </div>
                {/* Form */}
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Footer */}
      <Footer></Footer>
      {/* Footer */}
    </React.Fragment>
  );
};

export default EditAddOns;
