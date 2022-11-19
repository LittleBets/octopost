<?php

namespace App;

enum Status : string
{
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
    case RUNNING = 'running';
    case QUEUED = 'queued';
    case TRAINING = 'training';
}