import React, { useEffect, useState } from "react";
import {
    updateForm,
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";


const stateType = "pax";
const Courses = ({ pax_state  }) => {

    const dispatch = useDispatch();
    const courses_list = useSelector((state) => state.StepWizardReducer.courses_list);

    const selectCourse = (e, index) => {

        let courseId = e.currentTarget.getAttribute("data-course");
        let courseTitle = e.currentTarget.getAttribute("data-coursetitle");
        let coursePrice = e.currentTarget.getAttribute("data-courseprice");
    

    }

    const CoursesUI = ({ courseId, paxIndex }) => {

        return courses_list.map((course_obj, i) =>
            <React.Fragment key={i}>
                <div className="col-12 col-sm-6 col-md-6  custom-single_cabin-wrapper single-cabin-wrapper custom-d-flex"
                    data-course={course_obj.id}
                    data-coursetitle={course_obj.title}
                    data-courseprice={course_obj.course_price}
                    onClick={(e) => selectCourse(e, paxIndex)}
                >
                    <div className={`card ${courseId == course_obj.id ? "cls-border" : ""}`}>
                        <div className="main-card-body">
                            <div className="card-body">
                                <h5 className="card-title">{course_obj.title}</h5>
                                <span className="summary-text-info common-price-text">S$ {course_obj.course_price} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </React.Fragment>
        )
    }


    return (
        <React.Fragment>
            <section className="col-sm-12 col-md-10">
                <div className="row date-area border-rd date-wrapper-section course-wrapper">
                    {pax_state.length > 0 ? (
                        pax_state.map((pax, index) =>
                            <div className="p-2 row rm-padding-mobile" key={index} id={`pax_${index}`}>


                                <div className="col-md-5 itinerary-wrapper single-pax">
                                    <div className="cls bg-none people-count"><p className="num ">Person{index + 1}</p></div>
                                </div>


                                <div className="cabin-main-wrapper custom-cabin-wrapper">
                                    <div className="row no-gutters row-cabindetails-wrapper">
                                        <div className="col-md-12">
                                            <div className="row">
                                                <div className="card-deck">
                                                    <CoursesUI courseId={pax.course_id} paxIndex={index}></CoursesUI>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        )
                    ) : 'Loading...'}

                </div>
            </section>
        </React.Fragment>
    );
}

export default Courses;
