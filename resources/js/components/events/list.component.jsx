import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Button from 'react-bootstrap/Button'
import axios from 'axios';
import Swal from 'sweetalert2'
import { isEmpty } from 'lodash';

export default function List() {

    const [events, setEvents] = useState([])
    const [filters, setFilters] = useState([]);

    useEffect(()=>{
        fetchEvents() 
    },[])

    const fetchEvents = async () => {
        var parma = ''
        if(!isEmpty(filters)) {
            parma = "?filter="+filters
        }
        await axios.get(`http://localhost:8000/event${parma}`).then(({data})=>{
            setEvents(data.data)
        })
    }

    const handleChange =async (event) => {
        setFilters(event.target.value)
        var parma = ''
        if(!isEmpty(event.target.value)) {
            parma = "?filter="+event.target.value
        }
        await axios.get(`http://localhost:8000/event${parma}`).then(({data})=>{
            setEvents(data.data)
        })
    };
    

    const deleteEvent = async (id) => {
        const isConfirm = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            return result.isConfirmed
          });

          if(!isConfirm){
            return;
          }

          await axios.delete(`http://localhost:8000/event/${id}`).then(({data})=>{
            Swal.fire({
                icon:"success",
                text:data.message
            })
            fetchEvents()
          }).catch(({response:{data}})=>{
            Swal.fire({
                text:data.message,
                icon:"error"
            })
          })
    }

    return (
      <div className="container">
          <div className="row">
          
            <div className='col-12'>
            <label >
                Event Filter
                <select value={filters} onChange={handleChange}>
                    <option value="">Select</option>
                    <option value="finshedEvents">Finshed Events</option>
                    <option value="finshedEventsLast7Days">Finshed Events Last 7 Days</option>
                    <option value="upcomingEvents">Upcoming Events</option>
                    <option value="upcomingEventsWithIn7days">Upcoming Events Last 7 Days</option>
                </select>
            </label>
                <Link className='btn btn-primary mb-2 float-end' to={"/create/event"}>
                    Create Event
                </Link>
            </div>
            <div className="col-12">
                <div className="card card-body">
                    <div className="table-responsive">
                        <table className="table table-bordered mb-0 text-center">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Event Description</th>
                                    <th>Event Start Date</th>
                                    <th>Event End date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    events.length > 0 && (
                                        events.map((row, key)=>(
                                            <tr key={key}>
                                                <td>{row.title}</td>
                                                <td>{row.description}</td>
                                                <td>{row.startDate}</td>
                                                <td>{row.endDate}</td>
                                                <td>
                                                    <Link to={`/edit/event/${row.id}`} className='btn btn-success me-2'>
                                                        Edit
                                                    </Link>
                                                    <Button variant="danger" onClick={()=>deleteEvent(row.id)}>
                                                        Delete
                                                    </Button>
                                                </td>
                                            </tr>
                                        ))
                                    )
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>
      </div>
    )
}